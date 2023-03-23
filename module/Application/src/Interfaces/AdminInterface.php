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
		public function postArticle(string $title, string $subject, string $body) : AdminInterface|bool;

        public function removeArticle(int $article_id) : AdminInterface|bool;

        public function editArticle(int $article_id, array $edits = []) : AdminInterface|bool;




        // utility methods
        public function viewUserList() : array|bool;

        public function upcomingSchedule() : array|bool;

        public function setAccess(int $role_id, string $admin) : AdminInterface|bool;

        public function banUser(int $user_id, array $params = []) : AdminInterface|bool;

        public function unbanUser(int $user_id) : AdminInterface|bool;

        public function uploadFile(array $file, string $access = 'private') : AdminInterface|bool;

        public function removeFile(string $file) : AdminInterface|bool;

        public function changeFileStatus(string $file, string $access) : AdminInterface|bool;
	}
