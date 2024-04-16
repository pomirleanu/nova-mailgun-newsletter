<?php

    namespace Pomirleanu\MailgunNewsletter\Actions;

    use Illuminate\Bus\Queueable;
    use Illuminate\Queue\InteractsWithQueue;
    use Illuminate\Support\Carbon;
    use Illuminate\Support\Collection;
    use Illuminate\Support\Facades\URL;
    use Laravel\Nova\Actions\Action;
    use Laravel\Nova\Fields\ActionFields;
    use Laravel\Nova\Fields\Boolean;
    use Laravel\Nova\Fields\DateTime;
    use Laravel\Nova\Fields\Select;
    use Laravel\Nova\Fields\Text;
    use Laravel\Nova\Http\Requests\NovaRequest;
    use Pomirleanu\MailgunNewsletter\MailgunService;

    class SendNotificationToUser extends Action
    {

        use InteractsWithQueue, Queueable;

        /**
         * The displayable name of the action.
         *
         * @var string
         */
        public $name = 'Send Email via Mailgun';


        /**
         * Perform the action on the given models.
         *
         * @param  \Laravel\Nova\Fields\ActionFields  $fields
         * @param  \Illuminate\Support\Collection  $models
         *
         * @return mixed
         */
        public function handle(ActionFields $fields, Collection $models)
        {
            foreach ($models as $model) {
                $unsubscribeLink = $this->unsubscribeUrl($model);
                $response = app(MailgunService::class)->sendEmail([
                    'template_id' => $fields->template_id,
                    'subject'     => $fields->subject,
                    'from'        => $fields->from,
                    'reply_to'    => $fields->reply_to,
                    'track_clicks' => $fields->track_clicks,
                    'track_opens' => $fields->track_opens,
                    'send_at'     => $fields->send_at,
                    'use_local_time' => $fields->use_local_time,
                    'email'       => $model->email,
                    'name'        => $model->username,
                    'unsubscribe_link' => $unsubscribeLink,
                ]);

                if (!$response['success']) {
                    return Action::danger("Failed to send email to {$model->email}");
                }
            }
        }


        /**
         * Get the fields available on the action.
         *
         * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
         *
         * @return array
         */
        public function fields(NovaRequest $request)
        {
            return [
                Text::make('Subject', 'subject')
                    ->rules('max:255'),

                Select::make('Mailgun Template', 'template_id')
                    ->options($this->getTemplateOptions()),

                Select::make('Send From', 'from')
                    ->options([
                        'support@renderlion.com' => 'Support Team',
                        'alex@renderlion.com' => 'Alex',
                        'no-replay@renderlion.com' => 'No Replay',
                    ]),

                Select::make('Reply To', 'reply_to')
                    ->options([
                        'support@renderlion.com' => 'Support Team',
                        'alex@renderlion.com' => 'Alex',
                        'no-replay@renderlion.com' => 'No Replay',
                    ]),

                Boolean::make('Enable Click Tracking', 'track_clicks')
                    ->trueValue('yes')->falseValue('no')->default('yes'),

                Boolean::make('Enable Open Tracking', 'track_opens')
                    ->trueValue('yes')->falseValue('no')->default('yes'),

                Boolean::make('User Local Time', 'use_local_time')
                    ->trueValue('yes')->falseValue('no')->default('no'),

                DateTime::make('Send At', 'send_at')
                    ->help('Schedule when to send the email.')
            ];
        }

        protected function unsubscribeUrl($user)
        {
            return URL::temporarySignedRoute('unsubscribe.newsletter', Carbon::now()->addDays(90), [
                'id' => $user->getKey(),
                'hash' => sha1($user->getEmailForVerification()),
            ]);
        }

        protected function getTemplateOptions()
        {
            // Assuming MailgunService is injected or instantiated
            $mailgunService = app(MailgunService::class);
            $templates = $mailgunService->getTemplates();  // This should return an array of Template objects

            $options = [];
            foreach ($templates as $template) {
                // Assuming the Template object has methods getId() and getName()
                $options[$template->getName()] = $template->getName();
            }
            return $options;
        }
    }
