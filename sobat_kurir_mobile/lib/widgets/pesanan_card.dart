import 'package:flutter/material.dart';

class PesananCard extends StatelessWidget {
  final Map<String, dynamic> item;
  final Widget? action;

  const PesananCard({
    super.key,
    required this.item,
    this.action,
  });

  String value(String key) {
    final data = item[key];

    if (data == null) {
      return '-';
    }

    final text = data.toString();

    if (text.trim().isEmpty) {
      return '-';
    }

    return text;
  }

  String rupiah(dynamic data) {
    final number = double.tryParse(data.toString()) ?? 0;
    return 'Rp ${number.toStringAsFixed(0)}';
  }

  Widget buildRow(String label, String data) {
    return Padding(
      padding: const EdgeInsets.only(bottom: 4),
      child: Row(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          SizedBox(
            width: 92,
            child: Text(
              label,
              style: const TextStyle(color: Colors.black54),
            ),
          ),
          const Text(': '),
          Expanded(
            child: Text(
              data,
              style: const TextStyle(fontWeight: FontWeight.w500),
            ),
          ),
        ],
      ),
    );
  }

  @override
  Widget build(BuildContext context) {
    final kodeResi = value('kode_resi');
    final status = value('status_pesanan');
    final namaPengirim = value('nama_pengirim');
    final namaPenerima = value('nama_penerima');
    final kecamatanAsal = value('kecamatan_asal');
    final kecamatanTujuan = value('kecamatan_tujuan');
    final berat = value('berat');
    final totalHarga = rupiah(item['total_harga'] ?? 0);

    return Container(
      margin: const EdgeInsets.only(bottom: 14),
      padding: const EdgeInsets.all(16),
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(18),
        boxShadow: [
          BoxShadow(
            color: Colors.black.withOpacity(0.06),
            blurRadius: 14,
            offset: const Offset(0, 8),
          ),
        ],
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Text(
            kodeResi,
            style: const TextStyle(
              fontSize: 18,
              fontWeight: FontWeight.bold,
            ),
          ),
          const SizedBox(height: 10),
          buildRow('Status', status),
          buildRow('Pengirim', namaPengirim),
          buildRow('Penerima', namaPenerima),
          buildRow('Dari', kecamatanAsal),
          buildRow('Tujuan', kecamatanTujuan),
          buildRow('Berat', '$berat kg'),
          buildRow('Total', totalHarga),
          if (action != null)
            Padding(
              padding: const EdgeInsets.only(top: 12),
              child: SizedBox(
                width: double.infinity,
                child: action!,
              ),
            ),
        ],
      ),
    );
  }
}
