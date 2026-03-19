# Generate responsive image sizes from static/images/profile.jpg
$src = Join-Path $PSScriptRoot '..\static\images\profile.jpg' | Resolve-Path -ErrorAction SilentlyContinue
if (-not $src) { Write-Error "Source file not found at static/images/profile.jpg"; exit 1 }
$src = $src.Path
Add-Type -AssemblyName System.Drawing
try {
    $img = [System.Drawing.Image]::FromFile($src)
} catch {
    Write-Error "Failed to load image: $_"; exit 1
}

function Save-Jpeg([System.Drawing.Image] $image, [string] $dstPath, [int] $size, [int] $quality){
    $w = $image.Width; $h = $image.Height
    if ($w -gt $h) {
        $cropX = [int](($w - $h) / 2); $cropY = 0; $cropW = $h; $cropH = $h
    } else {
        $cropX = 0; $cropY = [int](($h - $w) / 2); $cropW = $w; $cropH = $w
    }
    $bmp = New-Object System.Drawing.Bitmap $size, $size
    $g = [System.Drawing.Graphics]::FromImage($bmp)
    $g.InterpolationMode = [System.Drawing.Drawing2D.InterpolationMode]::HighQualityBicubic
    $g.SmoothingMode = [System.Drawing.Drawing2D.SmoothingMode]::HighQuality
    $g.PixelOffsetMode = [System.Drawing.Drawing2D.PixelOffsetMode]::HighQuality
    $g.Clear([System.Drawing.Color]::White)
    $rect = New-Object System.Drawing.Rectangle 0,0,$size,$size
    $srcRect = New-Object System.Drawing.Rectangle $cropX, $cropY, $cropW, $cropH
    $g.DrawImage($image, $rect, $srcRect, [System.Drawing.GraphicsUnit]::Pixel)
    $enc = [System.Drawing.Imaging.ImageCodecInfo]::GetImageEncoders() | Where-Object { $_.MimeType -eq 'image/jpeg' }
    $encParams = New-Object System.Drawing.Imaging.EncoderParameters 1
    $encParams.Param[0] = New-Object System.Drawing.Imaging.EncoderParameter ([System.Drawing.Imaging.Encoder]::Quality, ([long] $quality))
    $bmp.Save($dstPath, $enc, $encParams)
    $g.Dispose(); $bmp.Dispose()
}

$outDir = Join-Path $PSScriptRoot '..\static\images' | Resolve-Path
$outDir = $outDir.Path

try {
    # create 200x200 optimized JPEG
    $dst200 = Join-Path $outDir 'profile-200.jpg'
    Save-Jpeg $img $dst200 200 75
    Write-Host "Saved: $dst200"
} catch {
    Write-Error "Failed to create responsive images: $_"; exit 1
}

$img.Dispose()
Get-ChildItem $outDir | Where-Object { $_.Name -match 'profile(-200|\.jpg)$' } | Select-Object FullName, Length
