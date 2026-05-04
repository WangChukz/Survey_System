<?php

declare(strict_types=1);

/**
 * Định nghĩa tất cả routes của ứng dụng.
 * Format: [HTTP_METHOD, URI_PATTERN, 'ControllerClass@method']
 */

return [
    // ── Trang chủ (Nhập thông tin) ───────────────────────────────────────
    ['GET',  '/',               'SurveyController@index'],

    // ── Bắt đầu khảo sát (Tạo session & attempt) ────────────────────────
    ['POST', '/survey/start',   'SurveyController@startSurvey'],

    // ── Hiển thị lô câu hỏi (Lấy ?batch=x) ──────────────────────────────
    ['GET',  '/survey',         'SurveyController@showBatch'],

    // ── Gửi câu trả lời của một lô ──────────────────────────────────────
    ['POST', '/survey/submit',  'SurveyController@submitBatch'],

    // ── Trang kết quả (Dashboard) ───────────────────────────────────────
    ['GET',  '/result',         'SurveyController@showResult'],

    // ── Trang Quản trị (Auth & Dashboard) ────────────────────────────────
    ['GET',  '/admin/login',    'AuthController@showLogin'],
    ['POST', '/admin/login',    'AuthController@authenticate'],
    ['GET',  '/admin/logout',   'AuthController@logout'],

    ['GET',  '/admin',          'AdminController@dashboard'],

    // ── Quản trị: Thêm câu hỏi (Question Management) ────────────────────
    ['GET',  '/admin/questions/add', 'AdminController@addQuestion'],
    ['POST', '/admin/questions/store', 'AdminController@storeQuestion'],
];
