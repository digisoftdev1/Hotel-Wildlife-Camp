<?php

namespace App\Services;

use App\Models\Room;
use App\Models\Blog;
use App\Models\Package;
use App\Models\ExperienceActivity;
use App\Models\Testimonial;
use App\Models\Service;
use App\Models\GalleryImage;
use App\Models\CustomerMessage;

class DashboardService
{
    /**
     * Get statistics for the dashboard.
     *
     * @return array
     */
    public function getStatistics(): array
    {
        return [
            'total_rooms' => Room::count(),
            'total_blogs' => Blog::count(),
            'total_packages' => Package::count(),
            'total_activities' => ExperienceActivity::count(),
            'total_testimonials' => Testimonial::count(),
            'total_services' => Service::count(),
            'total_gallery_images' => GalleryImage::count(),
            'total_messages' => CustomerMessage::count(),
            'unread_messages' => CustomerMessage::unread()->count(),
        ];
    }

    /**
     * Get recent activities or items for the dashboard.
     *
     * @return array
     */
    public function getRecentItems(): array
    {
        return [
            'recent_rooms' => Room::latest()->take(5)->get(['id', 'room_name as title', 'created_at']),
            'recent_blogs' => Blog::latest()->take(5)->get(['id', 'blog_title as title', 'created_at']),
            'recent_packages' => Package::latest()->take(5)->get(['id', 'name as title', 'created_at']),
            'recent_messages' => CustomerMessage::latest()->take(5)->get(['id', 'subject as title', 'name', 'status', 'created_at']),
        ];
    }
}
