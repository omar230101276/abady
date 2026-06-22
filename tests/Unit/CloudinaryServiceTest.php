<?php

namespace Tests\Unit;

use App\Services\CloudinaryService;
use Tests\TestCase;

class CloudinaryServiceTest extends TestCase
{
    protected CloudinaryService $cloudinaryService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->cloudinaryService = new CloudinaryService();
    }

    public function test_optimize_url_adds_transformations_to_cloudinary_url(): void
    {
        $url = 'https://res.cloudinary.com/demo/image/upload/v1571218681/photographer/albums/cover.jpg';
        $optimized = $this->cloudinaryService->optimizeUrl($url, 'w_300');
        
        $this->assertEquals(
            'https://res.cloudinary.com/demo/image/upload/w_300/v1571218681/photographer/albums/cover.jpg',
            $optimized
        );
    }

    public function test_optimize_url_ignores_non_cloudinary_urls(): void
    {
        $url = '/images/local-cover.jpg';
        $optimized = $this->cloudinaryService->optimizeUrl($url, 'w_300');
        
        $this->assertEquals($url, $optimized);
    }

    public function test_get_thumbnail_url_uses_correct_square_crop_parameters(): void
    {
        $url = 'https://res.cloudinary.com/demo/image/upload/v1571218681/photographer/albums/cover.jpg';
        $thumb = $this->cloudinaryService->getThumbnailUrl($url);
        
        $this->assertStringContainsString('c_fill,g_auto,w_400,h_400', $thumb);
    }

    public function test_extract_public_id_extracts_id_from_url_with_folders(): void
    {
        $url = 'https://res.cloudinary.com/demo/image/upload/v1571218681/photographer/albums/cover.jpg';
        $publicId = $this->cloudinaryService->extractPublicId($url);
        
        $this->assertEquals('photographer/albums/cover', $publicId);
    }

    public function test_extract_public_id_extracts_id_without_version(): void
    {
        $url = 'https://res.cloudinary.com/demo/image/upload/photographer/albums/cover.jpg';
        $publicId = $this->cloudinaryService->extractPublicId($url);
        
        $this->assertEquals('photographer/albums/cover', $publicId);
    }
}
