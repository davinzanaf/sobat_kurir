import 'package:flutter/material.dart';

import 'screens/customer/customer_home_screen.dart';
import 'screens/kurir/kurir_home_screen.dart';
import 'screens/login_screen.dart';
import 'screens/splash_screen.dart';

void main() {
  runApp(const SobatKurirApp());
}

class SobatKurirApp extends StatelessWidget {
  const SobatKurirApp({super.key});

  @override
  Widget build(BuildContext context) {
    return MaterialApp(
      title: 'Sobat Kurir',
      debugShowCheckedModeBanner: false,
      theme: ThemeData(
        colorSchemeSeed: Colors.blue,
        useMaterial3: true,
      ),
      initialRoute: SplashScreen.routeName,
      routes: {
        SplashScreen.routeName: (context) => const SplashScreen(),
        LoginScreen.routeName: (context) => const LoginScreen(),
        CustomerHomeScreen.routeName: (context) => const CustomerHomeScreen(),
        KurirHomeScreen.routeName: (context) => const KurirHomeScreen(),
      },
    );
  }
}
