<?php

namespace App\Enums;

enum Bodas: int
{
    case PAPEL = 1;
    case ALGODAO = 2;
    case TRIGO = 3;
    case FLORES = 4;
    case MADEIRA = 5;
    case PERFUME = 6;
    case LATAO = 7;
    case PAPOULA = 8;
    case CERAMICA = 9;
    case ESTANHO = 10;
    case ACO = 11;
    case ONIX = 12;
    case LINHO = 13;
    case MARFIM = 14;
    case CRISTAL = 15;
    case TURMALINA = 16;
    case ROSA = 17;
    case TURQUESA = 18;
    case CRETONE = 19;
    case PORCELANA = 20;
    case ZIRCAO = 21;
    case LOUCA = 22;
    case PALHA = 23;
    case OPALA = 24;
    case PRATA = 25;
    case ALEXANDRITA = 26;
    case CRISOPRASIO = 27;
    case HEMATITA = 28;
    case ERVA = 29;
    case PEROLA = 30;
    case NACAR = 31;
    case PINHO = 32;
    case CRIZO = 33;
    case OLIVEIRA = 34;
    case CORAL = 35;
    case CEDRO = 36;
    case AVENTURINA = 37;
    case CARVALHO = 38;
    case MARMORE = 39;
    case ESMERALDA = 40;
    case SEDA = 41;
    case PRATA_DOURADA = 42;
    case AZEVICHE = 43;
    case CARBONATO = 44;
    case RUBI = 45;
    case ALABASTRO = 46;
    case JASPE = 47;
    case GRANITO = 48;
    case HELIOTROPIO = 49;
    case OURO = 50;
    case BRONZE = 51;
    case ARGILA = 52;
    case ANTIMONIO = 53;
    case NIQUEL = 54;
    case AMETISTA = 55;
    case MALAQUITA = 56;
    case LAPIS_LAZULI = 57;
    case VIDRO = 58;
    case CEREJA = 59;
    case DIAMANTE = 60;
    case COBRE = 61;
    case TELURITA = 62;
    case LILAS = 63;
    case FABULITA = 64;
    case PLATINA = 65;
    case EBANO = 66;
    case NEVE = 67;
    case CHUMBO = 68;
    case MERCURIO = 69;
    case VINHO = 70;
    case ZINCO = 71;
    case AVEIA = 72;
    case MANJERONA = 73;
    case MACIEIRA = 74;
    case BRILHANTE = 75;
    case CIPRESTE = 76;
    case ALFAZEMA = 77;
    case BENJOIM = 78;
    case CAFE = 79;
    case CARVALHO2 = 80;
    case CACAU = 81;
    case CRAVO = 82;
    case BEGONIA = 83;
    case CRISANTEMO = 84;
    case GIRASSOL = 85;
    case HORTENSIA = 86;
    case NOGUEIRA = 87;
    case PERA = 88;
    case FIGUEIRA = 89;
    case ALAMO = 90;
    case PINHEIRO = 91;
    case SALGUEIRO = 92;
    case IMBUIA = 93;
    case PALMEIRA = 94;
    case SANDALO = 95;
    case OLIVEIRA2 = 96;
    case ABETO = 97;
    case PINHEIRO2 = 98;
    case SALGUEIRO2 = 99;
    case JEQUITIBA = 100;

    public function bodas(): string
    {
        return match ($this) {
            self::PAPEL => 'Bodas de Papel',
            self::ALGODAO => 'Bodas de Algodão',
            self::TRIGO => 'Bodas de Trigo ou de Couro',
            self::FLORES => 'Bodas de Flores e Frutas',
            self::MADEIRA => 'Bodas de Madeira ou Ferro',
            self::PERFUME => 'Bodas de Perfume ou Açúcar',
            self::LATAO => 'Bodas de Latão ou Lã',
            self::PAPOULA => 'Bodas de Papoula ou Barro',
            self::CERAMICA => 'Bodas de Cerâmica ou Vime',
            self::ESTANHO => 'Bodas de Estanho ou Zinco',
            self::ACO => 'Bodas de Aço',
            self::ONIX => 'Bodas de Ônix ou Seda',
            self::LINHO => 'Bodas de Linho ou Renda',
            self::MARFIM => 'Bodas de Marfim',
            self::CRISTAL => 'Bodas de Cristal',
            self::TURMALINA => 'Bodas de Turmalina',
            self::ROSA => 'Bodas de Rosa',
            self::TURQUESA => 'Bodas de Turquesa',
            self::CRETONE => 'Bodas de Cretone ou Água Marinha',
            self::PORCELANA => 'Bodas de Porcelana',
            self::ZIRCAO => 'Bodas de Zircão',
            self::LOUCA => 'Bodas de Louça',
            self::PALHA => 'Bodas de Palha',
            self::OPALA => 'Bodas de Opala',
            self::PRATA => 'Bodas de Prata',
            self::ALEXANDRITA => 'Bodas de Alexandrita',
            self::CRISOPRASIO => 'Bodas de Crisoprásio',
            self::HEMATITA => 'Bodas de Hematita',
            self::ERVA => 'Bodas de Erva',
            self::PEROLA => 'Bodas de Pérola',
            self::NACAR => 'Bodas de Nácar',
            self::PINHO => 'Bodas de Pinho',
            self::CRIZO => 'Bodas de Crizo',
            self::OLIVEIRA => 'Bodas de Oliveira',
            self::CORAL => 'Bodas de Coral',
            self::CEDRO => 'Bodas de Cedro',
            self::AVENTURINA => 'Bodas de Aventurina',
            self::CARVALHO => 'Bodas de Carvalho',
            self::MARMORE => 'Bodas de Mármore',
            self::ESMERALDA => 'Bodas de Esmeralda',
            self::SEDA => 'Bodas de Seda',
            self::PRATA_DOURADA => 'Bodas de Prata Dourada',
            self::AZEVICHE => 'Bodas de Azeviche',
            self::CARBONATO => 'Bodas de Carbonato',
            self::RUBI => 'Bodas de Rubi',
            self::ALABASTRO => 'Bodas de Alabastro',
            self::JASPE => 'Bodas de Jaspe',
            self::GRANITO => 'Bodas de Granito',
            self::HELIOTROPIO => 'Bodas de Heliotrópio',
            self::OURO => 'Bodas de Ouro',
            self::BRONZE => 'Bodas de Bronze',
            self::ARGILA => 'Bodas de Argila',
            self::ANTIMONIO => 'Bodas de Antimônio',
            self::NIQUEL => 'Bodas de Níquel',
            self::AMETISTA => 'Bodas de Ametista',
            self::MALAQUITA => 'Bodas de Malaquita',
            self::LAPIS_LAZULI => 'Bodas de Lápis-Lazuli',
            self::VIDRO => 'Bodas de Vidro',
            self::CEREJA => 'Bodas de Cereja',
            self::DIAMANTE => 'Bodas de Diamante',
            self::COBRE => 'Bodas de Cobre',
            self::TELURITA => 'Bodas de Telurita ou Alecrim',
            self::LILAS => 'Bodas de Lilás',
            self::FABULITA => 'Bodas de Fabulita',
            self::PLATINA => 'Bodas de Platina',
            self::EBANO => 'Bodas de Ébano',
            self::NEVE => 'Bodas de Neve',
            self::CHUMBO => 'Bodas de Chumbo',
            self::MERCURIO => 'Bodas de Mercúrio',
            self::VINHO => 'Bodas de Vinho',
            self::ZINCO => 'Bodas de Zinco',
            self::AVEIA => 'Bodas de Aveia',
            self::MANJERONA => 'Bodas de Manjerona',
            self::MACIEIRA => 'Bodas de Macieira',
            self::BRILHANTE => 'Bodas de Brilhante',
            self::CIPRESTE => 'Bodas de Cipreste',
            self::ALFAZEMA => 'Bodas de Alfazema',
            self::BENJOIM => 'Bodas de Benjoim',
            self::CAFE => 'Bodas de Café',
            self::CARVALHO2 => 'Bodas de Carvalho ',
            self::CACAU => 'Bodas de Cacau',
            self::CRAVO => 'Bodas de Cravo',
            self::BEGONIA => 'Bodas de Begônia',
            self::CRISANTEMO => 'Bodas de Crisântemo',
            self::GIRASSOL => 'Bodas de Girassol',
            self::HORTENSIA => 'Bodas de Hortênsia',
            self::NOGUEIRA => 'Bodas de Nogueira',
            self::PERA => 'Bodas de Pêra',
            self::FIGUEIRA => 'Bodas de Figueira',
            self::ALAMO => 'Bodas de Álamo',
            self::PINHEIRO => 'Bodas de Pinheiro',
            self::SALGUEIRO => 'Bodas de Salgueiro',
            self::IMBUIA => 'Bodas de Imbuia',
            self::PALMEIRA => 'Bodas de Palmeira',
            self::SANDALO => 'Bodas de Sândalo',
            self::OLIVEIRA2 => 'Bodas de Oliveira ',
            self::ABETO => 'Bodas de Abeto',
            self::PINHEIRO2 => 'Bodas de Pinheiro ',
            self::SALGUEIRO2 => 'Bodas de Salgueiro ',
            self::JEQUITIBA => 'Bodas de Jequitibá',
        };
    }

    public static function allBodas(): array
    {
        $list = [];
        foreach (Bodas::cases() as $bodas) {
            $list[] = array(
                'name' => Bodas::tryFrom($bodas->value)->bodas(),
                'id' => $bodas->value,
            );
        }

        return $list;
    }
}
