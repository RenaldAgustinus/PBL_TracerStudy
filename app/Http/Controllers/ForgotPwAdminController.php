<?php
// filepath: c:\laragon\www\PBL_TracerStudy\PBL_TracerStudy\app\Http\Controllers\ForgotPwAdminController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\Admin;
use Illuminate\Support\Facades\Log;

class ForgotPwAdminController extends Controller
{
    // public function showLinkRequestForm()
    // {
    //     return view('auth.forgot-password');
    // }

    public function sendResetLinkEmail(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email|exists:admin,email'
            ], [
                'email.required' => 'Email wajib diisi',
                'email.email' => 'Format email tidak valid',
                'email.exists' => 'Email tidak terdaftar dalam sistem'
            ]);

            $email = $request->email;
            
            // Check if there's already a recent reset request (rate limiting)
            $existingToken = DB::table('password_reset_tokens')
                ->where('email', $email)
                ->where('created_at', '>', Carbon::now()->subMinutes(5))
                ->first();

            if ($existingToken) {
                return response()->json([
                    'status' => false,
                    'message' => 'Link reset password sudah dikirim. Silakan cek email Anda atau tunggu 5 menit untuk mengirim ulang.'
                ], 200);
            }

            // Generate token
            $token = Str::random(64);

            // Delete old tokens for this email
            DB::table('password_reset_tokens')
                ->where('email', $email)
                ->delete();

            // Store new token
            DB::table('password_reset_tokens')->insert([
                'email' => $email,
                'token' => Hash::make($token),
                'created_at' => Carbon::now()
            ]);

            // Return success response with reset URL for testing
            return response()->json([
                'status' => true,
                'message' => 'Link reset password telah dikirim ke email Anda.',
                'redirect' => route('admin.password.reset', $token),
                'debug_info' => [
                    'token' => $token,
                    'reset_url' => route('admin.password.reset', $token)
                ]
            ]);
                
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi gagal',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Reset password error: ' . $e->getMessage());
            return response()->json([
                'status' => false,
                'message' => 'Terjadi kesalahan sistem. Silakan coba lagi.'
            ], 500);
        }
    }

    public function showResetForm(Request $request, $token = null)
    {
        return view('auth.resetpw', [
            'token' => $token,
            'email' => $request->email
        ]);
    }

    public function reset(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email|exists:admin,email',
                'password' => 'required|string|min:8|confirmed',
                'token' => 'required'
            ], [
                'email.required' => 'Email wajib diisi',
                'email.email' => 'Format email tidak valid',
                'email.exists' => 'Email tidak terdaftar',
                'password.required' => 'Password wajib diisi',
                'password.min' => 'Password minimal 8 karakter',
                'password.confirmed' => 'Konfirmasi password tidak cocok',
                'token.required' => 'Token tidak valid'
            ]);

            // Find the token
            $resetRecord = DB::table('password_reset_tokens')
                ->where('email', $request->email)
                ->orderBy('created_at', 'desc')
                ->first();

            if (!$resetRecord) {
                return back()->with('error', 'Token reset password tidak valid atau sudah expired.');
            }

            // Check if token matches
            if (!Hash::check($request->token, $resetRecord->token)) {
                return back()->with('error', 'Token reset password tidak valid.');
            }

            // Check if token is not expired (24 hours)
            if (Carbon::parse($resetRecord->created_at)->addHours(24)->isPast()) {
                DB::table('password_reset_tokens')->where('email', $request->email)->delete();
                return back()->with('error', 'Token reset password sudah expired. Silakan request ulang.');
            }

            // Update password
            $admin = Admin::where('email', $request->email)->first();
            $admin->update([
                'password' => Hash::make($request->password)
            ]);

            // Delete all tokens for this email
            DB::table('password_reset_tokens')->where('email', $request->email)->delete();

            return redirect()->route('login')->with('success', 'Password berhasil diubah. Silakan login dengan password baru.');

        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            Log::error('Update password error: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan sistem. Silakan coba lagi.');
        }
    }
}