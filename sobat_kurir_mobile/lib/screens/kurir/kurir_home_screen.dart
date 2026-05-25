import 'package:flutter/material.dart';

import '../../services/api_client.dart';
import '../../widgets/dashboard_card.dart';
import '../login_screen.dart';
import 'kurir_pesanan_saya_screen.dart';
import 'kurir_tugas_baru_screen.dart';

class KurirHomeScreen extends StatefulWidget {
  static const String routeName = '/kurir';

  const KurirHomeScreen({super.key});

  @override
  State<KurirHomeScreen> createState() => _KurirHomeScreenState();
}

class _KurirHomeScreenState extends State<KurirHomeScreen> {
  late Future<Map<String, dynamic>> futureDashboard;

  @override
  void initState() {
    super.initState();
    futureDashboard = getDashboard();
  }

  Future<Map<String, dynamic>> getDashboard() async {
    final result = await ApiClient.getJson('/kurir/dashboard', auth: true);

    if (result['status_code'] == 200 && result['success'] == true) {
      return result;
    }

    throw Exception(result['message'] ?? 'Gagal memuat dashboard kurir.');
  }

  Future<void> logout() async {
    await ApiClient.logout();

    if (!mounted) {
      return;
    }

    Navigator.pushReplacementNamed(context, LoginScreen.routeName);
  }

  void refreshDashboard() {
    setState(() {
      futureDashboard = getDashboard();
    });
  }

  Map<String, dynamic> getRingkasan(Map<String, dynamic> result) {
    final data = result['data'];

    if (data is Map<String, dynamic>) {
      final ringkasan = data['ringkasan'];

      if (ringkasan is Map<String, dynamic>) {
        return ringkasan;
      }
    }

    return <String, dynamic>{};
  }

  String readValue(Map<String, dynamic> map, String key) {
    return map[key]?.toString() ?? '0';
  }

  Widget buildBody() {
    return FutureBuilder<Map<String, dynamic>>(
      future: futureDashboard,
      builder: (context, snapshot) {
        if (snapshot.connectionState == ConnectionState.waiting) {
          return const Center(child: CircularProgressIndicator());
        }

        if (snapshot.hasError) {
          return Center(
            child: Padding(
              padding: const EdgeInsets.all(24),
              child: Text(
                'Error: ${snapshot.error}',
                textAlign: TextAlign.center,
              ),
            ),
          );
        }

        final result = snapshot.data ?? <String, dynamic>{};
        final ringkasan = getRingkasan(result);

        return RefreshIndicator(
          onRefresh: () async {
            refreshDashboard();
          },
          child: ListView(
            padding: const EdgeInsets.all(16),
            children: [
              DashboardCard(
                title: 'Tugas Baru',
                value: readValue(ringkasan, 'jumlah_tugas_baru'),
                icon: Icons.assignment_outlined,
                onTap: () async {
                  await Navigator.push(
                    context,
                    MaterialPageRoute(
                      builder: (context) => const KurirTugasBaruScreen(),
                    ),
                  );

                  refreshDashboard();
                },
              ),
              DashboardCard(
                title: 'Pesanan Saya',
                value: readValue(ringkasan, 'jumlah_pesanan_saya'),
                icon: Icons.local_shipping_outlined,
                onTap: () async {
                  await Navigator.push(
                    context,
                    MaterialPageRoute(
                      builder: (context) => const KurirPesananSayaScreen(),
                    ),
                  );

                  refreshDashboard();
                },
              ),
            ],
          ),
        );
      },
    );
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: const Color(0xfff4f7fb),
      appBar: AppBar(
        title: const Text('Dashboard Kurir'),
        actions: [
          IconButton(
            onPressed: refreshDashboard,
            icon: const Icon(Icons.refresh),
          ),
          IconButton(
            onPressed: logout,
            icon: const Icon(Icons.logout),
          ),
        ],
      ),
      body: buildBody(),
    );
  }
}
