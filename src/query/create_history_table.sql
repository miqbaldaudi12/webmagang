CREATE TABLE IF NOT EXISTS history (
    id_peserta INT NOT NULL,
    nama_peserta VARCHAR(100) NOT NULL,
    email_peserta VARCHAR(100) NOT NULL,
    telp_peserta VARCHAR(15) NOT NULL,
    alamat_peserta VARCHAR(200) NOT NULL,
    instansi VARCHAR(100) NOT NULL,
    id_mentor INT NULL,
    mentor VARCHAR(100) NOT NULL,
    tanggal_mulai DATE NOT NULL,
    tanggal_selesai DATE NOT NULL,
    status VARCHAR(20) NOT NULL,
    FOREIGN KEY (id_mentor) REFERENCES mentor(id_mentor)
);