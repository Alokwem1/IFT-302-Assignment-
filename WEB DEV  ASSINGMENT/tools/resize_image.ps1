$src = Join-Path $PSScriptRoot '..\static\images\profile.jpg' | Resolve-Path -ErrorAction SilentlyContinue
if (-not $src) { Write-Error "Source file not found"; exit 1 }
$src = $src.Path
$dst = $src
$size = 400
Add-Type -AssemblyName System.Drawing
try {
    $img = [System.Drawing.Image]::FromFile($src)
} catch {
    Write-Error "Failed to load image: $_"; exit 1
}
$w = $img.Width; $h = $img.Height
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
$g.DrawImage($img, $rect, $srcRect, [System.Drawing.GraphicsUnit]::Pixel)
# Save as JPEG with quality 85
$enc = [System.Drawing.Imaging.ImageCodecInfo]::GetImageEncoders() | Where-Object { $_.MimeType -eq 'image/jpeg' }
$encParams = New-Object System.Drawing.Imaging.EncoderParameters 1
$encParams.Param[0] = New-Object System.Drawing.Imaging.EncoderParameter ([System.Drawing.Imaging.Encoder]::Quality, 85L)
$bmp.Save($dst, $enc, $encParams)
$g.Dispose(); $bmp.Dispose(); $img.Dispose()
Write-Host "Resized image saved to $dst"; Get-Item $dst | Select-Object FullName, Length