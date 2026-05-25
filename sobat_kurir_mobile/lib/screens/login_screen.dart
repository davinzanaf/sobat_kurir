import 'package:flutter/material.dart';

import '../services/api_client.dart';
import 'customer/customer_home_screen.dart';
import 'kurir/kurir_home_screen.dart';

class LoginScreen extends StatefulWidget {
  static const String routeName = '/login';

  const LoginScreen({super.key});

  @override
  State<LoginScreen> createState() => _LoginScreenState();
}

class _LoginScreenState extends State<LoginScreen> {
  final TextEditingController emailController = TextEditingController(
    text: 'customer@test.com',
  );

  final TextEditingController passwordController = TextEditingController(
    text: 'password123',
  );

  bool isLoading = false;
  String? errorMessage;

  Future<void> login() async {
    setState(() {
      isLoading = true;
      errorMessage = null;
    });

    try {
      final result = await ApiClient.postJson('/login', {
        'email': emailController.text.trim(),
        'password': passwordController.text.trim(),
        'device_name': 'flutter-mobile',
      });

      final statusCode = result['status_code'];
      final isSuccess = result['success'] == true;

      if (statusCode == 200 && isSuccess) {
        final data = result['data'] as Map<String, dynamic>;
        final user = data['user'] as Map<String, dynamic>;

        final token = data['token'].toString();
        final role = user['role'].toString();
        final namaLengkap = user['nama_lengkap']?.toString() ?? '';
        final email = user['email']?.toString() ?? '';

        await ApiClient.saveSession(
          token: token,
          role: role,
          namaLengkap: namaLengkap,
          email: email,
        );

        if (!mounted) {
          return;
        }

        if (role == 'customer') {
          Navigator.pushReplacementNamed(context, CustomerHomeScreen.routeName);
          return;
        }

        if (role == 'kurir') {
          Navigator.pushReplacementNamed(context, KurirHomeScreen.routeName);
          return;
        }

        setState(() {
          errorMessage = 'Role tidak dikenali: $role';
        });

        return;
      }

      setState(() {
        errorMessage = result['message']?.toString() ?? 'Login gagal.';
      });
    } catch (error) {
      setState(() {
        errorMessage = 'Tidak bisa terhubung ke server: $error';
      });
    } finally {
      if (mounted) {
        setState(() {
          isLoading = false;
        });
      }
    }
  }

  @override
  void dispose() {
    emailController.dispose();
    passwordController.dispose();
    super.dispose();
  }

  Widget buildErrorMessage() {
    if (errorMessage == null) {
      return const SizedBox.shrink();
    }

    return Container(
      width: double.infinity,
      margin: const EdgeInsets.only(bottom: 16),
      padding: const EdgeInsets.all(12),
      decoration: BoxDecoration(
        color: Colors.red.shade50,
        borderRadius: BorderRadius.circular(12),
        border: Border.all(color: Colors.red.shade100),
      ),
      child: Text(
        errorMessage!,
        style: TextStyle(
          color: Colors.red.shade700,
          fontWeight: FontWeight.w500,
        ),
      ),
    );
  }

  Widget buildLoginButton() {
    return SizedBox(
      height: 52,
      child: ElevatedButton(
        onPressed: isLoading ? null : login,
        child: isLoading
            ? const SizedBox(
                width: 22,
                height: 22,
                child: CircularProgressIndicator(strokeWidth: 2),
              )
            : const Text(
                'Login',
                style: TextStyle(fontSize: 16),
              ),
      ),
    );
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: const Color(0xfff4f7fb),
      body: SafeArea(
        child: Center(
          child: SingleChildScrollView(
            padding: const EdgeInsets.all(24),
            child: ConstrainedBox(
              constraints: const BoxConstraints(maxWidth: 430),
              child: Container(
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
                  crossAxisAlignment: CrossAxisAlignment.stretch,
                  children: [
                    const Icon(
                      Icons.local_shipping_rounded,
                      size: 72,
                      color: Colors.blue,
                    ),
                    const SizedBox(height: 16),
                    const Text(
                      'Sobat Kurir',
                      textAlign: TextAlign.center,
                      style: TextStyle(
                        fontSize: 28,
                        fontWeight: FontWeight.bold,
                      ),
                    ),
                    const SizedBox(height: 8),
                    const Text(
                      'Login Customer atau Kurir',
                      textAlign: TextAlign.center,
                      style: TextStyle(color: Colors.black54),
                    ),
                    const SizedBox(height: 32),
                    TextField(
                      controller: emailController,
                      keyboardType: TextInputType.emailAddress,
                      decoration: const InputDecoration(
                        labelText: 'Email',
                        border: OutlineInputBorder(),
                        prefixIcon: Icon(Icons.email_outlined),
                      ),
                    ),
                    const SizedBox(height: 16),
                    TextField(
                      controller: passwordController,
                      obscureText: true,
                      decoration: const InputDecoration(
                        labelText: 'Password',
                        border: OutlineInputBorder(),
                        prefixIcon: Icon(Icons.lock_outline),
                      ),
                    ),
                    const SizedBox(height: 16),
                    buildErrorMessage(),
                    buildLoginButton(),
                    const SizedBox(height: 16),
                    const Text(
                      'tidak perlu kata-kata yang penting bukti nyata',
                      textAlign: TextAlign.center,
                      style: TextStyle(
                        color: Colors.black45,
                        fontSize: 12,
                      ),
                    ),
                  ],
                ),
              ),
            ),
          ),
        ),
      ),
    );
  }
}
