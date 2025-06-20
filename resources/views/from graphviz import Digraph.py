from graphviz import Digraph

# Inisialisasi grafik
dot = Digraph('Gambar1', format='png')
dot.attr(rankdir='LR')  # Arah kiri ke kanan
dot.attr('node', shape='box', style='filled', fillcolor='white', color='black', fontcolor='black', fontsize='11')

# Node
dot.node('A', 'Data Audio Mentah')
dot.node('B', 'Pra-pemrosesan & Augmentasi')
dot.node('C', 'Fitur Diekstraksi & Dinormalisasi')
dot.node('D', 'Penanganan Nilai NaN')
dot.node('E', 'Model CNN')
dot.node('F', 'Model CNN-BiLSTM')
dot.node('G', 'Probabilitas CNN')
dot.node('H', 'Probabilitas BiLSTM')
dot.node('I', 'Averaging Probabilitas')
dot.node('J', 'Prediksi Akhir')

# Edges (panah hubungan)
dot.edge('A', 'B')
dot.edge('B', 'C')
dot.edge('C', 'D')
dot.edge('D', 'E')
dot.edge('D', 'F')
dot.edge('E', 'G')
dot.edge('F', 'H')
dot.edge('G', 'I')
dot.edge('H', 'I')
dot.edge('I', 'J')

# Render dan simpan sebagai gambar
dot.render('gambar1_pendekatan_SER', view=False, cleanup=True)
print("Gambar berhasil dibuat dan disimpan sebagai 'gambar1_pendekatan_SER.png'")
