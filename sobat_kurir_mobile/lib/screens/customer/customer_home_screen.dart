import 'package:flutter/material.dart';

import '../../services/api_client.dart';
import '../../widgets/dashboard_card.dart';
import '../login_screen.dart';
import 'customer_buat_pesanan_screen.dart';
import 'customer_cek_ongkir_screen.dart';
import 'customer_lacak_paket.dart';
import 'customer_riwayat_pesanan_screen.dart';

class CustomerHomeScreen extends StatefulWidget {
  static const String routeName = '/customer';

  const CustomerHomeScreen({super.key});

  @override
  State<CustomerHomeScreen> createState() => _CustomerHomeScreenState();
}

class _CustomerHomeScreenState extends State<CustomerHomeScreen> {
  late Future<Map<String, dynamic>> futureDashboard;

  @override
  void initState() {
    super.initState();
    futureDashboard = getDashboard();
  }

  Future<Map<String, dynamic>> getDashboard() async {
    final result = await ApiClient.getJson('/customer/dashboard', auth: true);

    if (result['status_code'] == 200 && result['success'] == true) {
      return result;
    }

    throw Exception(result['message'] ?? 'Gagal memuat dashboard customer.');
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

  Future<void> openPage(Widget page) async {
    await Navigator.push(
      context,
      MaterialPageRoute(builder: (context) => page),
    );

    refreshDashboard();
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
                title: 'Jumlah Pesanan',
                value: readValue(ringkasan, 'jumlah_pesanan'),
                icon: Icons.inventory_2_outlined,
              ),
              DashboardCard(
                title: 'Menunggu Kurir',
                value: readValue(ringkasan, 'jumlah_menunggu'),
                icon: Icons.hourglass_empty,
              ),
              DashboardCard(
                title: 'Sedang Dikirim',
                value: readValue(ringkasan, 'jumlah_dikirim'),
                icon: Icons.local_shipping_outlined,
              ),
              DashboardCard(
                title: 'Selesai',
                value: readValue(ringkasan, 'jumlah_selesai'),
                icon: Icons.check_circle_outline,
              ),
              const SizedBox(height: 10),
              const Text(
                'Menu Customer',
                style: TextStyle(
                  fontSize: 18,
                  fontWeight: FontWeight.bold,
                ),
              ),
              const SizedBox(height: 12),
              DashboardCard(
                title: 'Cek Ongkir',
                value: '',
                icon: Icons.payments_outlined,
                onTap: () => openPage(const CustomerCekOngkirScreen()),
              ),
              DashboardCard(
                title: 'Buat Pesanan',
                value: '',
                icon: Icons.add_box_outlined,
                onTap: () => openPage(const CustomerBuatPesananScreen()),
              ),
              DashboardCard(
                title: 'Riwayat Pesanan',
                value: '',
                icon: Icons.history_outlined,
                onTap: () => openPage(const CustomerRiwayatPesananScreen()),
              ),
              DashboardCard(
                title: 'Lacak Paket',
                value: '',
                icon: Icons.search_outlined,
                onTap: () => openPage(const CustomerLacakPaketScreen()),
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
        title: const Text('Dashboard Customer'),
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
