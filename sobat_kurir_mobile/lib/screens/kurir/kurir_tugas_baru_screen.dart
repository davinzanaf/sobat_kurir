import 'package:flutter/material.dart';

import '../../services/api_client.dart';
import '../../widgets/pesanan_card.dart';

class KurirTugasBaruScreen extends StatefulWidget {
  const KurirTugasBaruScreen({super.key});

  @override
  State<KurirTugasBaruScreen> createState() => _KurirTugasBaruScreenState();
}

class _KurirTugasBaruScreenState extends State<KurirTugasBaruScreen> {
  late Future<List<Map<String, dynamic>>> futurePesanan;

  @override
  void initState() {
    super.initState();
    futurePesanan = getTugasBaru();
  }

  Future<List<Map<String, dynamic>>> getTugasBaru() async {
    final result = await ApiClient.getJson('/kurir/tugas-baru', auth: true);

    if (result['status_code'] == 200 && result['success'] == true) {
      final data = result['data'];

      if (data is Map<String, dynamic>) {
        final pesanan = data['pesanan'];

        if (pesanan is List) {
          return pesanan
              .whereType<Map>()
              .map((item) => Map<String, dynamic>.from(item))
              .toList();
        }
      }

      return <Map<String, dynamic>>[];
    }

    throw Exception(result['message'] ?? 'Gagal memuat tugas baru.');
  }

  Future<void> ambilPesanan(dynamic idPesanan) async {
    final result = await ApiClient.postJson(
      '/kurir/tugas-baru/$idPesanan/ambil',
      <String, dynamic>{},
      auth: true,
    );

    if (!mounted) {
      return;
    }

    final message = result['message']?.toString() ?? 'Response server diterima.';

    ScaffoldMessenger.of(context).showSnackBar(
      SnackBar(content: Text(message)),
    );

    if (result['status_code'] == 200 && result['success'] == true) {
      setState(() {
        futurePesanan = getTugasBaru();
      });
    }
  }

  Widget buildEmptyView() {
    return const Center(
      child: Text('Belum ada tugas baru.'),
    );
  }

  Widget buildErrorView(Object error) {
    return Center(
      child: Padding(
        padding: const EdgeInsets.all(24),
        child: Text(
          'Error: $error',
          textAlign: TextAlign.center,
        ),
      ),
    );
  }

  Widget buildList(List<Map<String, dynamic>> pesanan) {
    if (pesanan.isEmpty) {
      return buildEmptyView();
    }

    return RefreshIndicator(
      onRefresh: () async {
        setState(() {
          futurePesanan = getTugasBaru();
        });
      },
      child: ListView.builder(
        padding: const EdgeInsets.all(16),
        itemCount: pesanan.length,
        itemBuilder: (context, index) {
          final item = pesanan[index];
          final idPesanan = item['id_pesanan'];

          return PesananCard(
            item: item,
            action: ElevatedButton.icon(
              onPressed: () => ambilPesanan(idPesanan),
              icon: const Icon(Icons.assignment_turned_in_outlined),
              label: const Text('Ambil Pesanan'),
            ),
          );
        },
      ),
    );
  }

  Widget buildBody() {
    return FutureBuilder<List<Map<String, dynamic>>>(
      future: futurePesanan,
      builder: (context, snapshot) {
        if (snapshot.connectionState == ConnectionState.waiting) {
          return const Center(child: CircularProgressIndicator());
        }

        if (snapshot.hasError) {
          return buildErrorView(snapshot.error!);
        }

        return buildList(snapshot.data ?? <Map<String, dynamic>>[]);
      },
    );
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: const Color(0xfff4f7fb),
      appBar: AppBar(
        title: const Text('Tugas Baru'),
      ),
      body: buildBody(),
    );
  }
}
