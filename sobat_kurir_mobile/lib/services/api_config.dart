class ApiConfig {
  static const String baseUrl = 'http://192.168.1.3:8000/api/mobile';

  static String url(String endpoint) {
    if (endpoint.startsWith('/')) {
      return '$baseUrl$endpoint';
    }

    return '$baseUrl/$endpoint';
  }
}
