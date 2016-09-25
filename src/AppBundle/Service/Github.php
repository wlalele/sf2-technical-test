<?php

namespace AppBundle\Service;

class Github
{
    const APP_USER_AGENT = 'stadline';
    const GITHUB_API_URL = 'https://api.github.com/';
    const GITHUB_API_SEARCH_USER = 'search/users?q=%s';
    const GITHUB_API_LIST_USER_REPOS = 'users/%s/repos';

    /**
     * Execute a curl request and returns the result
     * @param $url
     * @return mixed
     */
    private function curlRequest($url)
    {
        $ch = curl_init($url);

        curl_setopt($ch, CURLOPT_USERAGENT, self::APP_USER_AGENT);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-type: application/json'));

        $output = curl_exec($ch);
        curl_close($ch);
        return $output;
    }

    /**
     * Check whether the user is on github or not
     * @param $username
     * @return bool
     */
    public function isOnGithub($username)
    {
        $url = self::GITHUB_API_URL.sprintf(self::GITHUB_API_SEARCH_USER, $username);
        $output = $this->curlRequest($url);
        $decoded = json_decode($output);
        return ($decoded->total_count > 0);
    }

    /**
     * Retrieve user repositories by username
     * @param $username
     * @return mixed
     */
    public function getUserRepositories($username)
    {
        $url = self::GITHUB_API_URL.sprintf(self::GITHUB_API_LIST_USER_REPOS, $username);
        $output = $this->curlRequest($url);
        return json_decode($output);
    }
}