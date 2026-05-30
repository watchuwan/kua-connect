<?php

namespace App\Http\Controllers;

use App\Models\Pendaftaran;
use Illuminate\Http\Request;

class TrackingController extends Controller
{
    public function index(Request $request)
    {
        $result = null;
        $nomor = $request->query('nomor_antrean');

        if ($nomor) {
            $result = Pendaftaran::with('pelayanan')
                ->where('nomor_antrean', $nomor)
                ->first();
        }

        return view('tracking.index', [
            'result' => $result,
            'nomor_antrean' => $nomor ?? old('nomor_antrean'),
        ]);
    }

    public function search(Request $request)
    {
        $request->validate([
            'nomor_antrean' => 'required|string|max:20',
        ]);

        $pendaftaran = Pendaftaran::with('pelayanan')
            ->where('nomor_antrean', $request->nomor_antrean)
            ->first();

        if (! $pendaftaran) {
            return redirect()->route('tracking.index')
                ->with('error', 'Nomor antrean tidak ditemukan.')
                ->withInput();
        }

        return view('tracking.index', [
            'result' => $pendaftaran,
            'nomor_antrean' => $pendaftaran->nomor_antrean,
        ]);
    }
}
