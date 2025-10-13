<?php

class DashboardModel extends Database {
    public function __construct() {
        parent::__construct();
    }

    // Get total events
    public function getTotalEvents() {
        $query = "SELECT COUNT(*) as total FROM events";
        $result = $this->qry($query)->fetch();
        return $result['total'];
    }

    // Get upcoming events (events yang start_time-nya di masa depan)
    public function getUpcomingEvents() {
        $query = "SELECT COUNT(*) as total FROM events 
                  WHERE start_time > NOW() AND status = 'published'";
        $result = $this->qry($query)->fetch();
        return $result['total'];
    }

    // Get completed events (events yang start_time-nya sudah lewat)
    public function getCompletedEvents() {
        $query = "SELECT COUNT(*) as total FROM events 
                  WHERE start_time <= NOW() AND status = 'published'";
        $result = $this->qry($query)->fetch();
        return $result['total'];
    }

    // Get total articles
    public function getTotalArticles() {
        $query = "SELECT COUNT(*) as total FROM articles";
        $result = $this->qry($query)->fetch();
        return $result['total'];
    }

    // Get published articles
    public function getPublishedArticles() {
        $query = "SELECT COUNT(*) as total FROM articles 
                  WHERE status = 'published'";
        $result = $this->qry($query)->fetch();
        return $result['total'];
    }

    // Get draft articles
    public function getDraftArticles() {
        $query = "SELECT COUNT(*) as total FROM articles 
                  WHERE status = 'draft'";
        $result = $this->qry($query)->fetch();
        return $result['total'];
    }

    // Get total admins (sebagai total user)
    public function getTotalUsers() {
        $query = "SELECT COUNT(*) as total FROM admins";
        $result = $this->qry($query)->fetch();
        return $result['total'];
    }

    // Get all statistics in one call (lebih efisien)
    public function getAllStatistics() {
        return [
            'total_events' => $this->getTotalEvents(),
            'upcoming_events' => $this->getUpcomingEvents(),
            'completed_events' => $this->getCompletedEvents(),
            'total_articles' => $this->getTotalArticles(),
            'published_articles' => $this->getPublishedArticles(),
            'draft_articles' => $this->getDraftArticles(),
            'total_users' => $this->getTotalUsers()
        ];
    }
}