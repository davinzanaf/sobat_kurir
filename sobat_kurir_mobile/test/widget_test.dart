import 'package:flutter_test/flutter_test.dart';
import 'package:sobat_kurir_mobile/main.dart';

void main() {
  testWidgets('Sobat Kurir app dapat dijalankan', (WidgetTester tester) async {
    await tester.pumpWidget(const SobatKurirApp());

    expect(find.text('Sobat Kurir'), findsWidgets);
  });
}
