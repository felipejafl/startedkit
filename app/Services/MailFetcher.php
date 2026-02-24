<?php

namespace App\Services;

use App\Models\MailAccount;
use Webklex\IMAP\Facades\Client as WebklexClient;

class MailFetcher
{
    
    /**
     * Fetch full message by UID from the account inbox.
     * Returns array with keys: uid, subject, from, date, html, text
     */
    public function fetchMessage(MailAccount $account, string $uid): array
    {
        $server = $account->server;
        $port = $account->imap_port ?: 993;
        $security = $account->imap_security ?: 'ssl';

        $config = [
            'host' => $server,
            'port' => $port,
            'encryption' => $security === 'none' ? 'notls' : $security,
            'validate_cert' => false,
            'username' => $account->email,
            'password' => $account->password,
            'protocol' => 'imap',
        ];

        try {
            $client = WebklexClient::make($config);
            $client->connect();

            $folder = $client->getFolder('INBOX');
            if ($folder === null) {
                throw new \RuntimeException('INBOX folder not found');
            }

            // Attempt to find the message by uid. Some servers/providers may not support direct lookup,
            // so we fetch a reasonable batch and match by uid.
            $messages = $folder->messages()->limit(200)->get();

            foreach ($messages as $msg) {
                if ((string) $msg->getUid() === (string) $uid) {
                    $html = method_exists($msg, 'getHTMLBody') ? $msg->getHTMLBody() : ($msg->getBody() ?: '');
                    $text = method_exists($msg, 'getTextBody') ? $msg->getTextBody() : strip_tags($html ?: ($msg->getBody() ?: ''));

                    return [
                        'uid' => $msg->getUid(),
                        'subject' => (string) $msg->getSubject() ?: '(No subject)',
                        'from' => (string) $msg->getFrom(),
                        'date' => ($msg->getDate() && method_exists($msg->getDate(), 'toDate')) ? $msg->getDate()->toDate()->toDateTimeString() : (string) $msg->getDate(),
                        'html' => $html,
                        'text' => $text,
                    ];
                }
            }

            throw new \RuntimeException('Message not found');
        } catch (\Throwable $e) {
            try {
                logger()->error('MailFetcher fetchMessage error: '.$e->getMessage(), [
                    'account_id' => $account->id ?? null,
                    'server' => $server ?? null,
                    'uid' => $uid,
                    'exception' => (string) $e,
                ]);
            } catch (\Throwable) {
                // ignore
            }

            throw new \RuntimeException('Mail fetch failed: '.$e->getMessage(), 0, $e);
        }
    }
    /**
     * Fetch message headers from the account inbox using webklex/laravel-imap.
     * Returns array of messages: [ ['uid'=>..., 'subject'=>..., 'from'=>..., 'date'=>...], ... ]
     */
    public function fetchHeaders(MailAccount $account, int $limit = 50): array
    {
        $server = $account->server;
        $port = $account->imap_port ?: 993;
        $security = $account->imap_security ?: 'ssl';

        $config = [
            'host' => $server,
            'port' => $port,
            'encryption' => $security === 'none' ? 'notls' : $security,
            'validate_cert' => false,
            'username' => $account->email,
            'password' => $account->password,
            'protocol' => 'imap',
        ];

        try {
            $client = WebklexClient::make($config);
            $client->connect();

            $folder = $client->getFolder('INBOX');

            if ($folder === null) {
                return [];
            }

            
            // Use the messages() entrypoint to build a query and fetch messages
            $messageQuery = $folder->messages()->all();

            // Avoid fetching full message bodies/attachments to keep memory usage low
            $messageQuery->fetchBody(false);

            $total = 0;
            try {
                $total = $messageQuery->count();
            } catch (\Throwable) {
                // Some providers or setups may not support count(), ignore and proceed to fetch
                $total = -1;
            }

            $messages = $messageQuery->limit($limit)->get();

            $result = [];
            foreach ($messages as $msg) {
                $result[] = [
                    'uid' => $msg->getUid(),
                    'subject' => (string) $msg->getSubject() ?: '(No subject)',
                    'from' => (string) $msg->getFrom(),
                    'date' => ($msg->getDate() && method_exists($msg->getDate(), 'toDate')) ? $msg->getDate()->toDate()->toDateTimeString() : (string) $msg->getDate(),
                ];
            }

            return $result;
        } catch (\Throwable $e) {
            // Log detailed error for debugging
            try {
                logger()->error('MailFetcher error: '.$e->getMessage(), [
                    'account_id' => $account->id ?? null,
                    'server' => $server ?? null,
                    'exception' => (string) $e,
                ]);
            } catch (\Throwable) {
                // ignore logging failures
            }

            throw new \RuntimeException('Mail fetch failed: '.$e->getMessage(), 0, $e);
        }
    }
}
