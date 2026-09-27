<?php

namespace App\Services;

class TalentNormalizationService
{
    public function normalize(array $data): array
    {
        if (array_key_exists('email', $data)) {
            $data['email'] = $this->normalizeEmail($data['email']);
        }

        if (array_key_exists('phone', $data)) {
            $data['phone'] = $this->normalizePhone($data['phone']);
        }

        if (array_key_exists('linkedin_url', $data)) {
            $data['linkedin_url'] = $this->normalizeLinkedIn($data['linkedin_url']);
        }

        if (array_key_exists('full_name', $data) && is_string($data['full_name'])) {
            $data['full_name'] = $this->normalizeName($data['full_name']);
        }

        return $data;
    }

    public function normalizeEmail(?string $email): ?string
    {
        if (empty($email)) {
            return null;
        }

        return strtolower(trim($email));
    }

    public function normalizePhone(?string $phone): ?string
    {
        if (empty($phone)) {
            return null;
        }

        // Remove spaces, dashes, parentheses
        $phone = preg_replace('/[\s\-\(\)]/', '', $phone);
        
        // Keep + prefix and digits only
        preg_match('/^(\+?\d+)/', $phone, $matches);
        
        return $matches[1] ?? null;
    }

    public function normalizeLinkedIn(?string $url): ?string
    {
        if (empty($url)) {
            return null;
        }

        $url = strtolower(trim($url));
        
        // Ensure https://
        if (!str_starts_with($url, 'http://') && !str_starts_with($url, 'https://')) {
            $url = 'https://' . $url;
        } else if (str_starts_with($url, 'http://')) {
            $url = 'https://' . substr($url, 7);
        }

        // Remove query params and trailing slashes
        $url = explode('?', $url)[0];
        $url = rtrim($url, '/');

        return $url;
    }

    public function normalizeName(string $name): string
    {
        return ucwords(strtolower(trim($name)));
    }
}
