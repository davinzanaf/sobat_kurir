import 'package:flutter/material.dart';

import '../../services/api_client.dart';

class CustomerCekOngkirScreen extends StatefulWidget {
  const CustomerCekOngkirScreen({super.key});

  @override
  State<CustomerCekOngkirScreen> createState() =>
      _CustomerCekOngkirScreenState();
}

class _CustomerCekOngkirScreenState extends State<CustomerCekOngkirScreen> {
  final TextEditingController beratController = TextEditingController(text: '1');

  List<String> asalOptions = <String>[];
  List<String> tujuanOptions = <String>[];

  String? selectedAsal;
  String? selectedTujuan;
  String? errorMessage;

  bool isLoadingOptions = true;
  bool isChecking = false;

  Map<String, dynamic>? hasilOngkir;

  @override
  void initState() {
    super.initState();
    loadTarifOptions();
  }

  Future<void> loadTarifOptions() async {
    setState(() {
      isLoadingOptions = true;
      errorMessage = null;
    });

    try {
      final result = await ApiClient.getJson('/customer/tarif/options', auth: true);

      if (result['status_code'] == 200 && result['success'] == true) {
        final data = result['data'] as Map<String, dynamic>;

        final asal = data['kecamatan_asal'];
        final tujuan = data['kecamatan_tujuan'];

        setState(() {
          asalOptions = asal is List
              ? asal.map((item) => item.toString()).toSet().toList()
              : <String>[];

          tujuanOptions = tujuan is List
              ? tujuan.map((item) => item.toString()).toSet().toList()
              : <String>[];

          if (asalOptions.isNotEmpty) {
            selectedAsal = asalOptions.first;
          }

          if (tujuanOptions.isNotEmpty) {
            selectedTujuan = tujuanOptions.first;
          }
        });

        return;
      }

      setState(() {
        errorMessage =
            result['message']?.toString() ?? 'Gagal memuat opsi tarif.';
      });
    } catch (error) {
      setState(() {
        errorMessage = 'Tidak bisa menghubungi server: $error';
      });
    } finally {
      if (mounted) {
        setState(() {
          isLoadingOptions = false;
        });
      }
    }
  }

  Future<void> cekOngkir() async {
    if (selectedAsal == null || selectedTujuan == null) {
      setState(() {
        errorMessage = 'Kecamatan asal dan tujuan wajib dipilih.';
        hasilOngkir = null;
      });
      return;
    }

    if (beratController.text.trim().isEmpty) {
      setState(() {
        errorMessage = 'Berat paket wajib diisi.';
        hasilOngkir = null;
      });
      return;
    }

    setState(() {
      isChecking = true;
      errorMessage = null;
      hasilOngkir = null;
    });

    try {
      final result = await ApiClient.postJson(
        '/customer/cek-ongkir',
        {
          'kecamatan_asal': selectedAsal,
          'kecamatan_tujuan': selectedTujuan,
          'berat': beratController.text.trim(),
        },
        auth: true,
      );

      if (result['status_code'] == 200 && result['success'] == true) {
        setState(() {
          hasilOngkir = result['data'] as Map<String, dynamic>;
        });

        return;
      }

      setState(() {
        errorMessage = result['message']?.toString() ?? 'Cek ongkir gagal.';
      });
    } catch (error) {
      setState(() {
        errorMessage = 'Tidak bisa menghubungi server: $error';
      });
    } finally {
      if (mounted) {
        setState(() {
          isChecking = false;
        });
      }
    }
  }

  String rupiah(dynamic value) {
    final number = double.tryParse(value.toString()) ?? 0;
    return 'Rp ${number.toStringAsFixed(0)}';
  }

  Widget buildDropdown({
    required String label,
    required String? value,
    required List<String> items,
    required ValueChanged<String?> onChanged,
  }) {
    return DropdownButtonFormField<String>(
      value: items.contains(value) ? value : null,
      items: items.map((item) {
        return DropdownMenuItem<String>(
          value: item,
          child: Text(item),
        );
      }).toList(),
      onChanged: onChanged,
      decoration: InputDecoration(
        labelText: label,
        border: const OutlineInputBorder(),
      ),
    );
  }

  Widget buildError() {
    if (errorMessage == null) {
      return const SizedBox.shrink();
    }

    return Container(
      width: double.infinity,
      margin: const EdgeInsets.only(top: 16),
      padding: const EdgeInsets.all(12),
      decoration: BoxDecoration(
        color: Colors.red.shade50,
        borderRadius: BorderRadius.circular(12),
        border: Border.all(color: Colors.red.shade100),
      ),
      child: Text(
        errorMessage!,
        style: TextStyle(color: Colors.red.shade700),
      ),
    );
  }

  Widget buildHasilOngkir() {
    if (hasilOngkir == null) {
      return const SizedBox.shrink();
    }

    return Container(
      width: double.infinity,
      margin: const EdgeInsets.only(top: 16),
      padding: const EdgeInsets.all(18),
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
          const Text(
            'Hasil Cek Ongkir',
            style: TextStyle(
              fontSize: 18,
              fontWeight: FontWeight.bold,
            ),
          ),
          const SizedBox(height: 12),
          Text('Asal: ${hasilOngkir!['kecamatan_asal']}'),
          Text('Tujuan: ${hasilOngkir!['kecamatan_tujuan']}'),
          Text('Berat: ${hasilOngkir!['berat']} kg'),
          Text('Harga per kg: ${rupiah(hasilOngkir!['harga_per_kg'])}'),
          const Divider(height: 24),
          Text(
            'Total: ${rupiah(hasilOngkir!['total_harga'])}',
            style: const TextStyle(
              fontSize: 22,
              fontWeight: FontWeight.bold,
              color: Colors.blue,
            ),
          ),
        ],
      ),
    );
  }

  @override
  void dispose() {
    beratController.dispose();
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    if (isLoadingOptions) {
      return Scaffold(
        backgroundColor: const Color(0xfff4f7fb),
        appBar: AppBar(title: const Text('Cek Ongkir')),
        body: const Center(child: CircularProgressIndicator()),
      );
    }

    return Scaffold(
      backgroundColor: const Color(0xfff4f7fb),
      appBar: AppBar(
        title: const Text('Cek Ongkir'),
      ),
      body: ListView(
        padding: const EdgeInsets.all(16),
        children: [
          Container(
            padding: const EdgeInsets.all(18),
            decoration: BoxDecoration(
              color: Colors.white,
              borderRadius: BorderRadius.circular(18),
            ),
            child: Column(
              children: [
                buildDropdown(
                  label: 'Kecamatan Asal',
                  value: selectedAsal,
                  items: asalOptions,
                  onChanged: (value) {
                    setState(() {
                      selectedAsal = value;
                    });
                  },
                ),
                const SizedBox(height: 14),
                buildDropdown(
                  label: 'Kecamatan Tujuan',
                  value: selectedTujuan,
                  items: tujuanOptions,
                  onChanged: (value) {
                    setState(() {
                      selectedTujuan = value;
                    });
                  },
                ),
                const SizedBox(height: 14),
                TextField(
                  controller: beratController,
                  keyboardType: TextInputType.number,
                  decoration: const InputDecoration(
                    labelText: 'Berat Paket',
                    suffixText: 'kg',
                    border: OutlineInputBorder(),
                    prefixIcon: Icon(Icons.scale_outlined),
                  ),
                ),
                const SizedBox(height: 16),
                SizedBox(
                  width: double.infinity,
                  height: 50,
                  child: ElevatedButton.icon(
                    onPressed: isChecking ? null : cekOngkir,
                    icon: isChecking
                        ? const SizedBox(
                            width: 18,
                            height: 18,
                            child: CircularProgressIndicator(strokeWidth: 2),
                          )
                        : const Icon(Icons.payments_outlined),
                    label: const Text('Cek Ongkir'),
                  ),
                ),
              ],
            ),
          ),
          buildError(),
          buildHasilOngkir(),
        ],
      ),
    );
  }
}
