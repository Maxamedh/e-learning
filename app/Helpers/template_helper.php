<?php

if (!function_exists('extract_template_content')) {
    /**
     * Extract main content and modals from HTML template files
     * 
     * @param string $templateFile Path to template file
     * @return array Array with 'main' and 'modals' keys
     */
    function extract_template_content($templateFile) {
        $fullPath = ROOTPATH . $templateFile;
        
        if (!file_exists($fullPath)) {
            return ['main' => '', 'modals' => ''];
        }
        
        $content = file_get_contents($fullPath);
        
        // Extract main-content section
        preg_match('/<div class="main-content">(.*?)<\/div>\s*<\/div>\s*<\/div>\s*<\/div>\s*<!-- Footer -->/s', $content, $mainMatches);
        
        // Extract modals (everything between <!--Create  Modal --> or <!--Edit  Modal --> and before <!-- Scripts -->)
        preg_match('/(<!--Create  Modal -->.*?)(<!-- Scripts -->)/s', $content, $modalMatches);
        
        $mainContent = isset($mainMatches[1]) ? $mainMatches[1] : '';
        $modals = isset($modalMatches[1]) ? $modalMatches[1] : '';
        
        // Replace asset paths
        $mainContent = str_replace('./assets/', base_url('assets/'), $mainContent);
        $modals = str_replace('./assets/', base_url('assets/'), $modals);
        
        // Replace common HTML links with routes
        $mainContent = str_replace('href="course-details.html"', 'href="' . base_url('course-details') . '"', $mainContent);
        $mainContent = str_replace('href="index.html"', 'href="' . base_url('/') . '"', $mainContent);
        
        return [
            'main' => $mainContent,
            'modals' => $modals
        ];
    }
}

