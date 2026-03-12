<?php
/**
 * Dynamic circuit diagram placeholder - generates SVG with project title
 * Usage: placeholder.php?title=Blink%20LED
 */
header('Content-Type: image/svg+xml');
header('Cache-Control: max-age=86400');

$title = isset($_GET['title']) ? htmlspecialchars(urldecode($_GET['title'])) : 'Circuit Diagram';
?>
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 400 320" fill="none" stroke="#9ca3af" stroke-width="1.5">
  <rect x="10" y="10" width="380" height="300" rx="10" fill="#f8fafc" stroke-dasharray="6 4"/>
  <circle cx="100" cy="90" r="25" fill="#e2e8f0"/>
  <circle cx="300" cy="90" r="25" fill="#e2e8f0"/>
  <path d="M125 90 H275 M100 115 L100 160 L300 160 L300 115" stroke-linecap="round"/>
  <rect x="170" y="175" width="60" height="35" rx="4" fill="#e2e8f0"/>
  <text x="200" y="235" text-anchor="middle" font-family="system-ui,sans-serif" font-size="16" font-weight="600" fill="#475569"><?php echo $title; ?></text>
  <text x="200" y="255" text-anchor="middle" font-family="system-ui,sans-serif" font-size="12" fill="#94a3b8">Circuit Diagram Placeholder</text>
</svg>
