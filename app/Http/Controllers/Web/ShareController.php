<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\ShareLink;
use Illuminate\Http\Response;
use Illuminate\View\View;

class ShareController extends Controller
{
    public function show(string $token): View|Response
    {
        $link = ShareLink::where('token', $token)->with('wedding')->firstOrFail();

        if (!$link->isValid()) {
            abort(410, 'Ce lien de partage a expiré ou a été désactivé.');
        }

        return view('share.show', compact('link'));
    }
}
