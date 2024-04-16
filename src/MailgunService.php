<?php

    namespace Pomirleanu\MailgunNewsletter;

    use DateTime;
    use Mailgun\Mailgun;

    class MailgunService
    {

        protected $mailgun;

        private mixed $domain;


        public function __construct()
        {
            $this->mailgun = Mailgun::create(env('MAILGUN_SECRET'));
            $this->domain  = env('MAILGUN_DOMAIN');
        }


        public function getLists()
        {
            $response = $this->mailgun->mailingList()->pages();

            return $response;
        }


        public function deleteList($address)
        {
            $response = $this->mailgun->mailingList()->delete($address);

            return $response;
        }


        public function getTemplates()
        {
            $response = $this->mailgun->templates()->index($this->domain, 100, 'first', 'nextPage')->getItems();

            return $response;
        }


        public function deleteTemplate($templateName)
        {
            return $this->mailgun->templates()->deleteTemplate($this->domain, $templateName);
        }


        public function sendEmail($data)
        {
            $params = [
                'from' => $data[ 'from' ],
                'to' => $data[ 'email' ],
                'template' => $data[ 'template_id' ],
                'h:X-Mailgun-Variables' => json_encode([
                    'name' => $data[ 'name' ],
                    'unsubscribe_link' => $data[ 'unsubscribe_link' ],
                ]),
                'o:tracking-clicks' => $data[ 'track_clicks' ],
                'o:tracking-opens' => $data[ 'track_opens' ],
                'o:tag' => $data[ 'template_id' ]
            ];

            if (! empty($data[ 'subject' ])) {
                $params[ 'subject' ] = str_replace('[NAME]', $data[ 'name' ], $data[ 'subject' ]);
            }

            if (! empty($data[ 'send_at' ])) {
                $params[ 'o:deliverytime' ] = (new DateTime($data[ 'send_at' ]))->format('D, d M Y H:i:s O');
            }

            try {
                $response = $this->mailgun->messages()->send($this->domain, $params);
                if (! $response) {
                    throw new \Exception("Mailgun send did not return a response.");
                }
            } catch (\Exception $e) {
                // Log the error or handle it appropriately
                return ['success' => false, 'message' => $e->getMessage()];
            }

            // Check the status of the response, handle based on actual Mailgun response structure
            if ($response->getId()) {
                return ['success' => true, 'message' => 'Email sent successfully'];
            }
            else {
                return ['success' => false, 'message' => 'Email sending failed'];
            }
        }

    }
