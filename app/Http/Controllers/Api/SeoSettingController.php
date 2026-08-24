<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\SeoSettingResource;
use App\Models\SeoSetting;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class SeoSettingController extends Controller
{
    // Known keys — keep in sync with the seoKey() of every ManageXSeo page.
    // An allowlist (rather than lazily creating a row for any string in the
    // URL) matches SectionHeadingController's pattern for the same reason:
    // an arbitrary key shouldn't be able to spam rows into the table.
    private const KEYS = ['home', 'about', 'initiatives', 'impact', 'gallery', 'get-involved', 'contact'];

    // GET /api/v1/seo-settings/{key}
    public function show(string $key): SeoSettingResource
    {
        if (! in_array($key, self::KEYS, true)) {
            throw new NotFoundHttpException();
        }

        return new SeoSettingResource(SeoSetting::forKey($key));
    }
}
