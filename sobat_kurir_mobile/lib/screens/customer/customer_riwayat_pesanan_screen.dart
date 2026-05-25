import 'package:flutter/material.dart';

import '../../services/api_client.dart';
import '../../widgets/pesanan_card.dart';
import 'customer_lacak_paket.dart';

class CustomerRiwayatPesananScreen extends StatefulWidget {
  const CustomerRiwayatPesananScreen({super.key});

  @override
  State<CustomerRiwayatPesananScreen> createState() =>
      _CustomerRiwayatPesananScreenState();
}

class _CustomerRiwayatPesananScreenState
    extends State<CustomerRiwayatPesananScreen> {
  final TextEditingController searchController = TextEditingController();

  late Future<List<Map<String, dynamic>>> futurePesanan;

  @override
  void initState() {
    super.initState();
    futurePesanan = getRiwayatPesanan();
  }

  Future<List<Map<String, dynamic>>> getRiwayatPesanan({String query = ''}) async {
    final encodedQuery = Uri.encodeQueryComponent(query.trim());

    final endpoint = encodedQuery.isEmpty
        ? '/customer/riwayat-pesanan'
        : '/customer/riwayat-pesanan?q=$encodedQuery';

    final result = await ApiClient.getJson(endpoint, auth: true);

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

    throw Exception(result['message'] ?? 'Gagal memuat riwayat pesanan.');
  }

  void searchPesanan() {
    setState(() {
      futurePesanan = getRiwayatPesanan(query: searchController.text);
    });
  }

  void refreshPesanan() {
    setState(() {
      futurePesanan = getRiwayatPesanan(query: searchController.text);
    });
  }

  Widget buildSearchBox() {
    return Container(
      margin: const EdgeInsets.fromLTRB(16, 16, 16, 8),
      padding: const EdgeInsets.all(14),
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(18),
      ),
      child: Row(
        children: [
          Expanded(
            child: TextField(
              controller: searchController,
              decoration: const InputDecoration(
                labelText: 'Cari resi / pengirim / penerima',
                border: OutlineInputBorder(),
                prefixIcon: Icon(Icons.search),
              ),
              onSubmitted: (_) => searchPesanan(),
            ),
          ),
          const SizedBox(width: 10),
          SizedBox(
            height: 56,
            child: ElevatedButton(
              onPressed: searchPesanan,
              child: const Icon(Icons.search),
            ),
          ),
        ],
      ),
    );
  }

  Widget buildEmptyView() {
    return const Expanded(
      child: Center(
        child: Text('Belum ada riwayat pesanan.'),
      ),
    );
  }

  Widget buildErrorView(Object error) {
    return Expanded(
      child: Center(
        child: Padding(
          padding: const EdgeInsets.all(24),
          child: Text(
            'Error: $error',
            textAlign: TextAlign.center,
          ),
        ),
      ),
    );
  }

  Widget buildList(List<Map<String, dynamic>> pesanan) {
    if (pesanan.isEmpty) {
      return buildEmptyView();
    }

    return Expanded(
      child: RefreshIndicator(
        onRefresh: () async {
          refreshPesanan();
        },
        child: ListView.builder(
          padding: const EdgeInsets.fromLTRB(16, 8, 16, 16),
          itemCount: pesanan.length,
          itemBuilder: (context, index) {
            final item = pesanan[index];

            return PesananCard(
              item: item,
              action: OutlinedButton.icon(
                onPressed: () {
                  Navigator.push(
                    context,
                    MaterialPageRoute(
                      builder: (context) => const CustomerLacakPaketScreen(),
                    ),
                  );
                },
                icon: const Icon(Icons.search_outlined),
                label: const Text('Lacak Paket'),
              ),
            );
          },
        ),
      ),
    );
  }

  Widget buildBody() {
    return Column(
      children: [
        buildSearchBox(),
        FutureBuilder<List<Map<String, dynamic>>>(
          future: futurePesanan,
          builder: (context, snapshot) {
            if (snapshot.connectionState == ConnectionState.waiting) {
              return const Expanded(
                child: Center(child: CircularProgressIndicator()),
              );
            }

            if (snapshot.hasError) {
              return buildErrorView(snapshot.error!);
            }

            return buildList(snapshot.data ?? <Map<String, dynamic>>[]);
          },
        ),
      ],
    );
  }

  @override
  void dispose() {
    searchController.dispose();
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: const Color(0xfff4f7fb),
      appBar: AppBar(
        title: const Text('Riwayat Pesanan'),
        actions: [
          IconButton(
            onPressed: refreshPesanan,
            icon: const Icon(Icons.refresh),
          ),
        ],
      ),
      body: buildBody(),
    );
  }
}
