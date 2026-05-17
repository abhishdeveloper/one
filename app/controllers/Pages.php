<?php
class Pages extends Controller {
    private $contentModel;

    public function __construct() {
        $this->contentModel = $this->model('ContentModel');
    }

    public function index() {
        $data = [
            'settings' => $this->contentModel->getSettings(),
            'services' => $this->contentModel->getServices(),
            'projects' => $this->contentModel->getProjects(),
            'testimonials' => $this->contentModel->getTestimonials()
        ];
        $this->view('pages/index', $data);
    }

    public function services() {
        $data = [
            'settings' => $this->contentModel->getSettings(),
            'services' => $this->contentModel->getServices()
        ];
        $this->view('pages/services', $data);
    }

    public function projects() {
        $data = [
            'settings' => $this->contentModel->getSettings(),
            'projects' => $this->contentModel->getProjects()
        ];
        $this->view('pages/projects', $data);
    }

    public function about() {
        $page = $this->contentModel->getPageBySlug('about');
        $data = [
            'settings' => $this->contentModel->getSettings(),
            'page' => $page
        ];
        $this->view('pages/dynamic_page', $data);
    }

    public function contact() {
        $data = [
            'settings' => $this->contentModel->getSettings(),
        ];
        $this->view('pages/contact', $data);
    }

    public function privacy() {
        $page = $this->contentModel->getPageBySlug('privacy-policy');
        $data = [
            'settings' => $this->contentModel->getSettings(),
            'page' => $page
        ];
        $this->view('pages/dynamic_page', $data);
    }

    public function terms() {
        $page = $this->contentModel->getPageBySlug('terms');
        $data = [
            'settings' => $this->contentModel->getSettings(),
            'page' => $page
        ];
        $this->view('pages/dynamic_page', $data);
    }

    public function faqs() {
        $faqs = $this->contentModel->getFaqs();
        $data = [
            'settings' => $this->contentModel->getSettings(),
            'faqs' => $faqs
        ];
        $this->view('pages/faqs', $data);
    }
}
