<?php

    namespace Pomirleanu\MailgunNewsletter\Http\Controllers;

    use Illuminate\Http\Request;
    use Illuminate\Routing\Controller;
    use Pomirleanu\MailgunNewsletter\MailgunService;

    class MailgunNewsletterController extends Controller
    {

        protected $mailgunService;


        public function __construct(MailgunService $mailgunService)
        {
            $this->mailgunService = $mailgunService;
        }


        public function getLists()
        {
            try {
                $lists = $this->mailgunService->getLists()->getLists();
                // Convert each list into a simpler array structure if necessary
                $listData = array_map(function ($list) {
                    return [
                        'name' => $list->getName(),
                        'address' => $list->getAddress(),
                        'description' => $list->getDescription(),
                        'replay' => $list->getReplyPreference(),
                        'members_count' => $list->getMembersCount(),
                        'created_at' => $list->getCreatedAt(),
                    ];
                }, $lists);

                return response()->json(['lists' => $listData]);
            } catch (\Exception $e) {
                return response()->json(['error' => $e->getMessage()], 500);
            }
        }


        public function deleteList(Request $request,$address)
        {
            try {
                $this->mailgunService->deleteList($address);
            } catch (\Exception $e) {
                return response()->json(['error' => $e->getMessage()], 500);
            }
        }


        public function getTemplates()
        {
            try {
                $templates    = $this->mailgunService->getTemplates();
                $templateData = array_map(function ($template) {
                    return [
                        'id' => $template->getId(),
                        'name' => $template->getName(),
                        'description' => $template->getDescription(),
                        'created_at' => $template->getCreatedAt(),
                    ];
                }, $templates);

                return response()->json(['templates' => $templateData]);
            } catch (\Exception $e) {
                return response()->json(['error' => $e->getMessage()], 500);
            }
        }

        public function deleteTemplate(Request $request,$templateName)
        {
            try {
                $this->mailgunService->deleteTemplate($templateName);
            } catch (\Exception $e) {
                return response()->json(['error' => $e->getMessage()], 500);
            }
        }
    }
