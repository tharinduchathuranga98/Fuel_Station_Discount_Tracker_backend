<?php
namespace App\Helpers;

use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;

class QrCodeHelper
{
    public static function generateQrCode($data)
    {
        $qrCode = QrCode::create($data);
        $writer = new PngWriter();
        $result = $writer->write($qrCode);

        return '' . base64_encode($result->getString());
    }
}

