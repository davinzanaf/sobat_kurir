import 'dart:convert';

import 'package:http/http.dart' as http;
import 'package:shared_preferences/shared_preferences.dart';

import 'api_config.dart';

class ApiClient {
  static Future<Map<String, dynamic>> getJson(
    String endpoint, {
    bool auth = false,
  }) async {
    final headers = await _headers(auth: auth);

    final response = await http.get(
      Uri.parse(ApiConfig.url(endpoint)),
      headers: headers,
    );

    return _decodeResponse(response);
  }

  static Future<Map<String, dynamic>> postJson(
    String endpoint,
    Map<String, dynamic> body, {
    bool auth = false,
  }) async {
    final headers = await _headers(auth: auth);

    final response = await http.post(
      Uri.parse(ApiConfig.url(endpoint)),
      headers: headers,
      body: jsonEncode(body),
    );

    return _decodeResponse(response);
  }

  static Future<Map<String, dynamic>> patchJson(
    String endpoint,
    Map<String, dynamic> body, {
    bool auth = false,
  }) async {
    final headers = await _headers(auth: auth);

    final response = await http.patch(
      Uri.parse(ApiConfig.url(endpoint)),
      headers: headers,
      body: jsonEncode(body),
    );

    return _decodeResponse(response);
  }

  static Future<Map<String, String>> _headers({required bool auth})async {
    final headers = <String, String>{
      'Accept': 'application/json',
      'Content-Type': 'application/json',
    };

    if (auth) {
      final token = await getToken();

      if (token != null && token.isNotEmpty) {
        headers['Authorization'] = 'Bearer $token';
      }
    }

    return headers;
  }

  static Map<String, dynamic> _decodeResponse(http.Response response) {
    final body = response.body.trim();

    if (body.isEmpty) {
      return {
        'success': false,
        'message': 'Response server kosong.',
        'status_code': response.statusCode,
      };
    }

    final decoded = jsonDecode(body);

    if (decoded is Map<String, dynamic>) {
      decoded['status_code'] = response.statusCode;
      return decoded;
    }

    return {
      'success': false,
      'message': 'Format response server tidak valid.',
      'status_code': response.statusCode,
      'raw': decoded,
    };
  }

  static Future<void> saveSession({
    required String token,
    required String role,
    required String namaLengkap,
    required String email,
  }) async {
    final prefs = await SharedPreferences.getInstance();

    await prefs.setString('token', token);
    await prefs.setString('role', role);
    await prefs.setString('nama_lengkap', namaLengkap);
    await prefs.setString('email', email);
  }

  static Future<String?> getToken() async {
    final prefs = await SharedPreferences.getInstance();
    return prefs.getString('token');
  }

  static Future<String?> getRole() async {
    final prefs = await SharedPreferences.getInstance();
    return prefs.getString('role');
  }

  static Future<String?> getNamaLengkap() async {
    final prefs = await SharedPreferences.getInstance();
    return prefs.getString('nama_lengkap');
  }

  static Future<void> clearSession() async {
    final prefs = await SharedPreferences.getInstance();
    await prefs.clear();
  }

  static Future<void> logout() async {
    try {
      await postJson('/logout', <String, dynamic>{}, auth: true);
    } finally {
      await clearSession();
    }
  }
}
