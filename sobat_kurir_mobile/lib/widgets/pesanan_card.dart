import 'package:flutter/material.dart';
import 'package:flutter/services.dart';

class PesananCard extends StatelessWidget {
  final Map<String, dynamic> item;
  final Widget? action;
  final bool showCopyResiButton;

  const PesananCard({
    super.key,
    required this.item,
    this.action,
    this.showCopyResiButton = false,
  });

  String value(String key) {
    final data = item[key];

    if (data == null) {
      return '-';
    }

    final text = data.toString().trim();

    if (text.isEmpty) {
      return '-';
    }

    return text;
  }

  String rupiah(dynamic data) {
    final number = double.tryParse(data.toString()) ?? 0;
    return 'Rp ${number.toStringAsFixed(0)}';
  }

  Future<void> copyKodeResi(BuildContext context) async {
    final kodeResi = value('kode_resi');

    if (kodeResi == '-') {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(
          content: Text('Kode resi tidak tersedia.'),
        ),
      );
      return;
    }

    try {
      await Clipboard.setData(
        ClipboardData(text: kodeResi),
      );

      if (!context.mounted) {
        return;
      }

      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(
          content: Text('Kode resi "$kodeResi" berhasil disalin.'),
          duration: const Duration(seconds: 2),
        ),
      );
    } catch (error) {
      if (!context.mounted) {
        return;
      }

      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(
          content: Text(
            'Gagal menyalin otomatis. Tekan lama kode resi lalu salin manual: $kodeResi',
          ),
          duration: const Duration(seconds: 4),
        ),
      );
    }
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

  Widget buildKodeResiSection(BuildContext context, String kodeResi) {
    return Container(
      width: double.infinity,
      padding: const EdgeInsets.all(12),
      decoration: BoxDecoration(
        color: const Color(0xfff4f7fb),
        borderRadius: BorderRadius.circular(14),
        border: Border.all(color: Colors.black12),
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          const Text(
            'Kode Resi',
            style: TextStyle(
              color: Colors.black54,
              fontSize: 12,
            ),
          ),
          const SizedBox(height: 4),
          SelectableText(
            kodeResi,
            style: const TextStyle(
              fontSize: 18,
              fontWeight: FontWeight.bold,
            ),
          ),
          if (showCopyResiButton) ...[
            const SizedBox(height: 10),
            SizedBox(
              width: double.infinity,
              height: 46,
              child: ElevatedButton.icon(
                onPressed: () => copyKodeResi(context),
                icon: const Icon(Icons.copy_outlined),
                label: const Text('Salin Kode Resi'),
              ),
            ),
          ],
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
          buildKodeResiSection(context, kodeResi),
          const SizedBox(height: 12),
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
