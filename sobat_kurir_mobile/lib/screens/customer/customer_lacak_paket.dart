import 'package:flutter/material.dart';

import '../../services/api_client.dart';

class CustomerLacakPaketScreen extends StatefulWidget {
  const CustomerLacakPaketScreen({super.key});

  @override
  State<CustomerLacakPaketScreen> createState() =>
      _CustomerLacakPaketScreenState();
}

class _CustomerLacakPaketScreenState extends State<CustomerLacakPaketScreen> {
  final TextEditingController kodeResiController = TextEditingController();

  bool isLoading = false;
  String? errorMessage;
  Map<String, dynamic>? trackingResult;

  Future<void> lacakPaket() async {
    final kodeResi = kodeResiController.text.trim();

    if (kodeResi.isEmpty) {
      setState(() {
        errorMessage = 'Kode resi wajib diisi.';
        trackingResult = null;
      });
      return;
    }

    setState(() {
      isLoading = true;
      errorMessage = null;
      trackingResult = null;
    });

    try {
      final result = await ApiClient.getJson('/tracking/$kodeResi', auth: true);

      if (result['status_code'] == 200 && result['success'] == true) {
        setState(() {
          trackingResult = result;
        });
        return;
      }

      setState(() {
        errorMessage = result['message']?.toString() ?? 'Data tracking gagal dimuat.';
      });
    } catch (error) {
      setState(() {
        errorMessage = 'Tidak bisa menghubungi server: $error';
      });
    } finally {
      if (mounted) {
        setState(() {
          isLoading = false;
        });
      }
    }
  }

  String readText(dynamic value) {
    if (value == null) {
      return '-';
    }

    final text = value.toString();

    if (text.trim().isEmpty) {
      return '-';
    }

    return text;
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

  Widget buildDataMap(Map<String, dynamic> data) {
    final widgets = <Widget>[];

    data.forEach((key, value) {
      if (value is Map<String, dynamic>) {
        widgets.add(
          Padding(
            padding: const EdgeInsets.only(top: 12, bottom: 6),
            child: Text(
              key,
              style: const TextStyle(
                fontWeight: FontWeight.bold,
                fontSize: 16,
              ),
            ),
          ),
        );

        widgets.add(buildDataMap(value));
      } else if (value is List) {
        widgets.add(
          Padding(
            padding: const EdgeInsets.only(top: 12, bottom: 6),
            child: Text(
              key,
              style: const TextStyle(
                fontWeight: FontWeight.bold,
                fontSize: 16,
              ),
            ),
          ),
        );

        if (value.isEmpty) {
          widgets.add(const Text('-'));
        } else {
          for (final item in value) {
            widgets.add(
              Container(
                width: double.infinity,
                margin: const EdgeInsets.only(bottom: 8),
                padding: const EdgeInsets.all(12),
                decoration: BoxDecoration(
                  color: const Color(0xfff4f7fb),
                  borderRadius: BorderRadius.circular(12),
                ),
                child: item is Map<String, dynamic>
                    ? buildDataMap(item)
                    : Text(readText(item)),
              ),
            );
          }
        }
      } else {
        widgets.add(
          Padding(
            padding: const EdgeInsets.only(bottom: 8),
            child: Row(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                SizedBox(
                  width: 120,
                  child: Text(
                    key,
                    style: const TextStyle(color: Colors.black54),
                  ),
                ),
                const Text(': '),
                Expanded(
                  child: Text(
                    readText(value),
                    style: const TextStyle(fontWeight: FontWeight.w500),
                  ),
                ),
              ],
            ),
          ),
        );
      }
    });

    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: widgets,
    );
  }

  Widget buildTrackingResult() {
    if (trackingResult == null) {
      return const SizedBox.shrink();
    }

    final data = trackingResult!['data'];

    if (data is! Map<String, dynamic>) {
      return Container(
        width: double.infinity,
        margin: const EdgeInsets.only(top: 16),
        padding: const EdgeInsets.all(16),
        decoration: BoxDecoration(
          color: Colors.white,
          borderRadius: BorderRadius.circular(18),
        ),
        child: Text(readText(data)),
      );
    }

    return Container(
      width: double.infinity,
      margin: const EdgeInsets.only(top: 16),
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
      child: buildDataMap(data),
    );
  }

  @override
  void dispose() {
    kodeResiController.dispose();
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: const Color(0xfff4f7fb),
      appBar: AppBar(
        title: const Text('Lacak Paket'),
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
                TextField(
                  controller: kodeResiController,
                  decoration: const InputDecoration(
                    labelText: 'Kode Resi',
                    hintText: 'Masukkan kode resi',
                    border: OutlineInputBorder(),
                    prefixIcon: Icon(Icons.receipt_long_outlined),
                  ),
                  textInputAction: TextInputAction.search,
                  onSubmitted: (_) => lacakPaket(),
                ),
                const SizedBox(height: 14),
                SizedBox(
                  width: double.infinity,
                  height: 50,
                  child: ElevatedButton.icon(
                    onPressed: isLoading ? null : lacakPaket,
                    icon: isLoading
                        ? const SizedBox(
                            width: 18,
                            height: 18,
                            child: CircularProgressIndicator(strokeWidth: 2),
                          )
                        : const Icon(Icons.search),
                    label: const Text('Lacak Sekarang'),
                  ),
                ),
              ],
            ),
          ),
          buildError(),
          buildTrackingResult(),
        ],
      ),
    );
  }
}
