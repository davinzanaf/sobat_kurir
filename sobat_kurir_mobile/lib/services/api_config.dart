class ApiConfig {
  static const String baseUrl = 'http://192.168.14.10:8000/api/mobile';

  static String url(String endpoint) {
    if (endpoint.startsWith('/')) {
      return '$baseUrl$endpoint';
    }

    return '$baseUrl/$endpoint';
  }
}
