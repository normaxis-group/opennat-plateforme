<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

/** Espace du compte : accès à l'outil et au recueil, historique des calculs. */
class EspaceController extends Controller
{
    public function __invoke(Request $request): View
    {
        return view('espace', [
            'calculs' => $request->user()->calculs()->latest()->limit(50)->get(),
        ]);
    }
}
