<?php
class ContentModel {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function getSettings() {
        $this->db->query("SELECT * FROM settings");
        $results = $this->db->resultSet();
        $settings = [];
        foreach ($results as $row) {
            $settings[$row->setting_key] = $row->setting_value;
        }
        return $settings;
    }

    public function getServices() {
        $this->db->query("SELECT * FROM services ORDER BY sort_order ASC");
        return $this->db->resultSet();
    }

    public function getProjects() {
        $this->db->query("SELECT * FROM projects ORDER BY sort_order ASC");
        return $this->db->resultSet();
    }

    public function getTestimonials() {
        $this->db->query("SELECT * FROM testimonials ORDER BY sort_order ASC");
        return $this->db->resultSet();
    }

    public function getPageBySlug($slug) {
        $this->db->query("SELECT * FROM pages WHERE slug = :slug");
        $this->db->bind(':slug', $slug);
        return $this->db->single();
    }

    public function getFaqs() {
        $this->db->query("SELECT * FROM faqs ORDER BY sort_order ASC");
        return $this->db->resultSet();
    }
}
