import 'package:flutter/material.dart';

import '../services/api_client.dart';
import 'customer/customer_home_screen.dart';
import 'kurir/kurir_home_screen.dart';
import 'login_screen.dart';

class SplashScreen extends StatefulWidget {
  static const String routeName = '/';

  const SplashScreen({super.key});

  @override
  State<SplashScreen> createState() => _SplashScreenState();
}

class _SplashScreenState extends State<SplashScreen> {
  String loadingText = 'Memeriksa sesi login...';

  @override
  void initState() {
    super.initState();
    checkSession();
  }

  Future<void> checkSession() async {
    await Future.delayed(const Duration(milliseconds: 700));

    final token = await ApiClient.getToken();
    final role = await ApiClient.getRole();

    if (!mounted) {
      return;
    }

    if (token == null || token.isEmpty || role == null || role.isEmpty) {
      Navigator.pushReplacementNamed(context, LoginScreen.routeName);
      return;
    }

    try {
      setState(() {
        loadingText = 'Menghubungkan ke server...';
      });

      final result = await ApiClient.getJson('/me', auth: true);

      if (!mounted) {
        return;
      }

      if (result['status_code'] == 200 && result['success'] == true) {
        if (role == 'customer') {
          Navigator.pushReplacementNamed(context, CustomerHomeScreen.routeName);
          return;
        }

        if (role == 'kurir') {
          Navigator.pushReplacementNamed(context, KurirHomeScreen.routeName);
          return;
        }
      }

      await ApiClient.clearSession();

      if (!mounted) {
        return;
      }

      Navigator.pushReplacementNamed(context, LoginScreen.routeName);
    } catch (error) {
      if (!mounted) {
        return;
      }

      setState(() {
        loadingText = 'Gagal menghubungkan ke server.';
      });

      await Future.delayed(const Duration(milliseconds: 900));

      if (!mounted) {
        return;
      }

      Navigator.pushReplacementNamed(context, LoginScreen.routeName);
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: const Color(0xfff4f7fb),
      body: SafeArea(
        child: Center(
          child: Container(
            width: 260,
            padding: const EdgeInsets.all(24),
            decoration: BoxDecoration(
              color: Colors.white,
              borderRadius: BorderRadius.circular(24),
              boxShadow: [
                BoxShadow(
                  color: Colors.black.withOpacity(0.08),
                  blurRadius: 20,
                  offset: const Offset(0, 10),
                ),
              ],
            ),
            child: Column(
              mainAxisSize: MainAxisSize.min,
              children: [
                const Icon(
                  Icons.local_shipping_rounded,
                  size: 74,
                  color: Colors.blue,
                ),
                const SizedBox(height: 18),
                const Text(
                  'Sobat Kurir',
                  style: TextStyle(
                    fontSize: 26,
                    fontWeight: FontWeight.bold,
                  ),
                ),
                const SizedBox(height: 8),
                Text(
                  loadingText,
                  textAlign: TextAlign.center,
                  style: const TextStyle(
                    color: Colors.black54,
                  ),
                ),
                const SizedBox(height: 22),
                const SizedBox(
                  width: 28,
                  height: 28,
                  child: CircularProgressIndicator(strokeWidth: 3),
                ),
              ],
            ),
          ),
        ),
      ),
    );
  }
}
