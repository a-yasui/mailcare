<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Email;
use App\Inbox;
use App\Attachment;
use App\Sender;
use \PhpMimeMailParser\Parser;
use Illuminate\Support\Facades\Storage;
use App\Events\EmailReceived;

/**
 *
 */
class ReceiveEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mailcare:email-receive {file=stream : The file of the email}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Receive email from mail server to integrate to database';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info("Receiving email ...");
        $file = $this->argument('file');
        $this->info("from $file");

        $parser = $this->getParser($file);

        /** @var Sender $sender */
        $sender = \DB::transaction( function () use ( $parser ){
            // UUIDv4でユニークなる設計にしているため、BCCで飛ばされたら二通目以降が死ぬのを対処
            $result = null;
            foreach($parser->getAddresses( 'from' ) as $num => $item){
                usleep( 1000 * 0.2 );
                $result = Sender::updateOrCreate(
                    [ 'email' => $item[ "address" ] ],
                    [ 'display_name' => $item[ "display" ], ]
                );
            }
            return $result;
        }, 3 );

        /** @var Inbox $inbox */
        $inbox = \DB::transaction( function () use ( $parser ){
            // UUIDv4でユニークなる設計にしているため、BCCで飛ばされたら二通目以降が死ぬのを対処
            $result = null;
            foreach($parser->getAddresses( 'to' ) as $num => $tos){
                usleep( 1000 * 0.2 ); // 0.2秒
                $result = Inbox::updateOrCreate(
                    [ 'email' => $tos[ "address" ] ],
                    [ 'display_name' => $tos[ "display" ], ]
                );
            }
            return $result;
        }, 3 );

        $email = new Email;
        $email->subject = $parser->getHeader('subject');

        if (!empty($parser->getMessageBody('html'))) {
            $email->has_html = true;
        }
        if (!empty($parser->getMessageBody('text'))) {
            $email->has_text = true;
        }

        $email->sender()->associate($sender);

        $inbox->emails()->save($email);

        Storage::put($email->path(), ($file == 'stream') ? $this->rawEmail : file_get_contents($file));

        $email->size_in_bytes = Storage::size($email->path());

        $email->save();

        foreach ($parser->getAttachments() as $attachmentParsed) {
            $attachment = new Attachment;
            $attachment->email_id = $email->id;
            $attachment->headers_hashed = $attachment->hashHeaders($attachmentParsed->getHeaders());
            $attachment->file_name = $attachmentParsed->getFileName();
            $attachment->content_type = $attachmentParsed->getContentType();
            $attachment->size_in_bytes = strlen($attachmentParsed->getMimePartStr());
            $attachment->save();
        }

        event(new EmailReceived($email->fresh()));
        return 0;
    }

    /**
     * @codeCoverageIgnore
     */
    protected function getParser($file)
    {
        $parser = new Parser;

        if ($file == 'stream') {
            $this->info("from stream");
            $fd = fopen("php://stdin", "r");
            $this->rawEmail = "";
            while (!feof($fd)) {
                $this->rawEmail .= fread($fd, 1024);
            }
            fclose($fd);
            $parser->setText($this->rawEmail);
        } else {
            $this->info("from path $file");
            $parser->setPath($file);
        }
        return $parser;
    }
}
