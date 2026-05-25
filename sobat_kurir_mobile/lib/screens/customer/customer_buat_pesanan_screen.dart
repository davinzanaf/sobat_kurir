import 'package:flutter/material.dart';

import '../../services/api_client.dart';

class CustomerBuatPesananScreen extends StatefulWidget {
  const CustomerBuatPesananScreen({super.key});

  @override
  State<CustomerBuatPesananScreen> createState() =>
      _CustomerBuatPesananScreenState();
}

class _CustomerBuatPesananScreenState extends State<CustomerBuatPesananScreen> {
  final TextEditingController namaPengirimController = TextEditingController();
  final TextEditingController noHpPengirimController = TextEditingController();
  final TextEditingController alamatPengirimController = TextEditingController();

  final TextEditingController namaPenerimaController = TextEditingController();
  final TextEditingController noHpPenerimaController = TextEditingController();
  final TextEditingController alamatPenerimaController = TextEditingController();

  final TextEditingController beratController = TextEditingController(text: '1');

  List<String> asalOptions = <String>[];
  List<String> tujuanOptions = <String>[];

  String? selectedAsal;
  String? selectedTujuan;
  String selectedMetodePembayaran = 'COD';

  bool isLoadingOptions = true;
  bool isSubmitting = false;

  String? errorMessage;

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

  bool validateForm() {
    if (namaPengirimController.text.trim().isEmpty ||
        noHpPengirimController.text.trim().isEmpty ||
        alamatPengirimController.text.trim().isEmpty ||
        namaPenerimaController.text.trim().isEmpty ||
        noHpPenerimaController.text.trim().isEmpty ||
        alamatPenerimaController.text.trim().isEmpty ||
        beratController.text.trim().isEmpty ||
        selectedAsal == null ||
        selectedTujuan == null) {
      setState(() {
        errorMessage = 'Semua field wajib diisi.';
      });

      return false;
    }

    return true;
  }

  Future<void> buatPesanan() async {
    if (!validateForm()) {
      return;
    }

    setState(() {
      isSubmitting = true;
      errorMessage = null;
    });

    try {
      final result = await ApiClient.postJson(
        '/customer/pesanan',
        {
          'nama_pengirim': namaPengirimController.text.trim(),
          'no_hp_pengirim': noHpPengirimController.text.trim(),
          'alamat_pengirim': alamatPengirimController.text.trim(),
          'nama_penerima': namaPenerimaController.text.trim(),
          'no_hp_penerima': noHpPenerimaController.text.trim(),
          'alamat_penerima': alamatPenerimaController.text.trim(),
          'kecamatan_asal': selectedAsal,
          'kecamatan_tujuan': selectedTujuan,
          'berat': beratController.text.trim(),
          'metode_pembayaran': selectedMetodePembayaran,
        },
        auth: true,
      );

      if (!mounted) {
        return;
      }

      if ((result['status_code'] == 200 || result['status_code'] == 201) &&
          result['success'] == true) {
        final data = result['data'] as Map<String, dynamic>;
        final kodeResi = data['kode_resi']?.toString() ?? '-';

        await showDialog<void>(
          context: context,
          builder: (context) {
            return AlertDialog(
              title: const Text('Pesanan Berhasil'),
              content: Text(
                'Pesanan berhasil dibuat.\n\nKode Resi:\n$kodeResi',
              ),
              actions: [
                TextButton(
                  onPressed: () => Navigator.pop(context),
                  child: const Text('OK'),
                ),
              ],
            );
          },
        );

        if (!mounted) {
          return;
        }

        Navigator.pop(context, true);
        return;
      }

      setState(() {
        errorMessage =
            result['message']?.toString() ?? 'Pesanan gagal dibuat.';
      });
    } catch (error) {
      setState(() {
        errorMessage = 'Tidak bisa menghubungi server: $error';
      });
    } finally {
      if (mounted) {
        setState(() {
          isSubmitting = false;
        });
      }
    }
  }

  Widget buildInput({
    required TextEditingController controller,
    required String label,
    required IconData icon,
    TextInputType keyboardType = TextInputType.text,
    int maxLines = 1,
  }) {
    return Padding(
      padding: const EdgeInsets.only(bottom: 14),
      child: TextField(
        controller: controller,
        keyboardType: keyboardType,
        maxLines: maxLines,
        decoration: InputDecoration(
          labelText: label,
          border: const OutlineInputBorder(),
          prefixIcon: Icon(icon),
        ),
      ),
    );
  }

  Widget buildDropdown({
    required String label,
    required String? value,
    required List<String> items,
    required ValueChanged<String?> onChanged,
  }) {
    return Padding(
      padding: const EdgeInsets.only(bottom: 14),
      child: DropdownButtonFormField<String>(
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
      ),
    );
  }

  Widget buildSectionTitle(String title) {
    return Padding(
      padding: const EdgeInsets.only(bottom: 12, top: 8),
      child: Text(
        title,
        style: const TextStyle(
          fontSize: 17,
          fontWeight: FontWeight.bold,
        ),
      ),
    );
  }

  Widget buildError() {
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
        style: TextStyle(color: Colors.red.shade700),
      ),
    );
  }

  @override
  void dispose() {
    namaPengirimController.dispose();
    noHpPengirimController.dispose();
    alamatPengirimController.dispose();
    namaPenerimaController.dispose();
    noHpPenerimaController.dispose();
    alamatPenerimaController.dispose();
    beratController.dispose();
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    if (isLoadingOptions) {
      return Scaffold(
        backgroundColor: const Color(0xfff4f7fb),
        appBar: AppBar(title: const Text('Buat Pesanan')),
        body: const Center(child: CircularProgressIndicator()),
      );
    }

    return Scaffold(
      backgroundColor: const Color(0xfff4f7fb),
      appBar: AppBar(
        title: const Text('Buat Pesanan'),
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
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                buildError(),
                buildSectionTitle('Data Pengirim'),
                buildInput(
                  controller: namaPengirimController,
                  label: 'Nama Pengirim',
                  icon: Icons.person_outline,
                ),
                buildInput(
                  controller: noHpPengirimController,
                  label: 'No HP Pengirim',
                  icon: Icons.phone_outlined,
                  keyboardType: TextInputType.phone,
                ),
                buildInput(
                  controller: alamatPengirimController,
                  label: 'Alamat Pengirim',
                  icon: Icons.location_on_outlined,
                  maxLines: 3,
                ),
                buildSectionTitle('Data Penerima'),
                buildInput(
                  controller: namaPenerimaController,
                  label: 'Nama Penerima',
                  icon: Icons.person_outline,
                ),
                buildInput(
                  controller: noHpPenerimaController,
                  label: 'No HP Penerima',
                  icon: Icons.phone_outlined,
                  keyboardType: TextInputType.phone,
                ),
                buildInput(
                  controller: alamatPenerimaController,
                  label: 'Alamat Penerima',
                  icon: Icons.location_on_outlined,
                  maxLines: 3,
                ),
                buildSectionTitle('Detail Pengiriman'),
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
                buildInput(
                  controller: beratController,
                  label: 'Berat Paket',
                  icon: Icons.scale_outlined,
                  keyboardType: TextInputType.number,
                ),
                DropdownButtonFormField<String>(
                  value: selectedMetodePembayaran,
                  items: const [
                    DropdownMenuItem(
                      value: 'COD',
                      child: Text('COD'),
                    ),
                    DropdownMenuItem(
                      value: 'TRANSFER',
                      child: Text('Transfer'),
                    ),
                  ],
                  onChanged: (value) {
                    if (value == null) {
                      return;
                    }

                    setState(() {
                      selectedMetodePembayaran = value;
                    });
                  },
                  decoration: const InputDecoration(
                    labelText: 'Metode Pembayaran',
                    border: OutlineInputBorder(),
                  ),
                ),
                const SizedBox(height: 18),
                SizedBox(
                  width: double.infinity,
                  height: 52,
                  child: ElevatedButton.icon(
                    onPressed: isSubmitting ? null : buatPesanan,
                    icon: isSubmitting
                        ? const SizedBox(
                            width: 18,
                            height: 18,
                            child: CircularProgressIndicator(strokeWidth: 2),
                          )
                        : const Icon(Icons.add_box_outlined),
                    label: const Text('Buat Pesanan'),
                  ),
                ),
              ],
            ),
          ),
        ],
      ),
    );
  }
}
