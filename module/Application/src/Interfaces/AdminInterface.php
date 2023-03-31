<?php
	
	namespace Application\Interfaces;


    interface AdminInterface
	{

        // meeting methods
        public function getRequestedMeetings() : array|bool;

        public function getConfirmedMeetings() : array|bool;

        public function confirmMeeting(array $details) : AdminInterface|bool;

        public function cancelMeeting(array $meeting, array $params = []) : AdminInterface|bool;

        public function rescheduleMeeting(array $meeting_id, array $params = []) : AdminInterface|bool;

        public function startMeeting(array $meeting_id) : bool;



        // article methods
		public function postArticle(string $subject, string $title, string $body, string $image) : AdminInterface|bool;

        public function uploadArticleImage(array $image) : AdminInterface|bool;

        public function removeArticle(int $article_id) : AdminInterface|bool;

        public function editArticle(int $article_id, array $edits = []) : AdminInterface|bool;

        public function viewArticles() : array;




        // utility methods
        public function viewUserList() : array|bool;

        public function upcomingSchedule() : array|bool;
	}
