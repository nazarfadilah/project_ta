import os
import PIL
from PIL import Image, ImageDraw, ImageFont

# Define coordinates layout
# Width = 960px
# Height = 1200px

ACTORS = {
    "admin": {"name": "Admin", "x": 100, "y": 300},
    "petugas": {"name": "Petugas", "x": 100, "y": 700},
    "pimpinan": {"name": "Pimpinan", "x": 860, "y": 300},
    "tamu": {"name": "Tamu", "x": 860, "y": 700}
}

USE_CASES = [
    {"label": "Login & Logout (Login Sistem)", "actors": ["admin", "petugas", "tamu", "pimpinan"]},
    {"label": "Registrasi Akun", "actors": ["tamu"]},
    {"label": "Kelola Data Users", "actors": ["admin"]},
    {"label": "Kelola Landing Page / Tentang", "actors": ["admin"]},
    {"label": "Kelola Data Tamu", "actors": ["admin", "petugas"]},
    {"label": "Kelola Data Ulasan", "actors": ["tamu"]},
    {"label": "Kelola Data Ruangan", "actors": ["petugas"]},
    {"label": "Kelola Data Sarana & Prasarana", "actors": ["petugas"]},
    {"label": "Kelola Paket Ruangan", "actors": ["petugas"]},
    {"label": "Kelola Profil", "actors": ["tamu"]},
    {"label": "Kelola Data Invoice", "actors": ["petugas"]},
    {"label": "Check-In Peminjaman", "actors": ["petugas"]},
    {"label": "Check-Out Peminjaman", "actors": ["petugas"]},
    {"label": "Verifikasi Peminjaman", "actors": ["petugas"]},
    {"label": "Ajukan Peminjaman (Permohonan)", "actors": ["tamu"]},
    {"label": "Batalkan Peminjaman (Pembatalan)", "actors": ["tamu"]},
    {"label": "Lihat Laporan Penggunaan", "actors": ["admin", "pimpinan"]},
    {"label": "Kelola Berita", "actors": ["admin", "petugas"]},
    {"label": "Publish Berita", "actors": ["admin"]}
]

def get_font():
    paths = [
        "C:\\Windows\\Fonts\\segoeui.ttf",
        "C:\\Windows\\Fonts\\arial.ttf",
        "segoeui.ttf",
        "arial.ttf"
    ]
    for path in paths:
        try:
            font_title = ImageFont.truetype(path, 15)
            font_bold = ImageFont.truetype(path, 12)
            font_regular = ImageFont.truetype(path, 11)
            return font_title, font_bold, font_regular
        except IOError:
            continue
    fallback = ImageFont.load_default()
    return fallback, fallback, fallback

font_title, font_bold, font_regular = get_font()

def wrap_text(text, font, max_width, draw):
    words = text.split(' ')
    lines = []
    current_line = []
    for word in words:
        test_line = ' '.join(current_line + [word])
        bbox = draw.textbbox((0, 0), test_line, font=font)
        w = bbox[2] - bbox[0]
        if w <= max_width:
            current_line.append(word)
        else:
            if current_line:
                lines.append(' '.join(current_line))
                current_line = [word]
            else:
                lines.append(word)
                current_line = []
    if current_line:
        lines.append(' '.join(current_line))
    return lines

def draw_text_centered(draw, text, x, y, font, fill):
    bbox = draw.textbbox((0, 0), text, font=font)
    w = bbox[2] - bbox[0]
    h = bbox[3] - bbox[1]
    draw.text((x - w / 2, y - h / 2), text, font=font, fill=fill)

def draw_use_case(draw, x, y, label, font):
    w = 280
    h = 44
    draw.ellipse([x - w//2, y - h//2, x + w//2, y + h//2], fill=(255, 255, 255), outline=(0, 0, 0), width=2)
    
    max_w = w - 20
    lines = wrap_text(label, font, max_w, draw)
    
    line_heights = []
    for line in lines:
        bbox = draw.textbbox((0, 0), line, font=font)
        line_heights.append(bbox[3] - bbox[1] + 2)
    total_h = sum(line_heights)
    
    curr_y = y - total_h//2
    for line in lines:
        bbox = draw.textbbox((0, 0), line, font=font)
        lw = bbox[2] - bbox[0]
        lh = bbox[3] - bbox[1]
        draw.text((x - lw/2, curr_y), line, font=font, fill=(0, 0, 0))
        curr_y += lh + 2

def draw_stick_figure(draw, x, y, name, font):
    # Head
    draw.ellipse([x - 12, y - 30, x + 12, y - 6], fill=(255, 255, 255), outline=(0, 0, 0), width=2)
    # Torso
    draw.line([x, y - 6, x, y + 24], fill=(0, 0, 0), width=2)
    # Arms
    draw.line([x - 20, y + 4, x + 20, y + 4], fill=(0, 0, 0), width=2)
    # Legs
    draw.line([x, y + 24, x - 15, y + 54], fill=(0, 0, 0), width=2)
    draw.line([x, y + 24, x + 15, y + 54], fill=(0, 0, 0), width=2)
    # Name text
    bbox = draw.textbbox((0, 0), name, font=font)
    w = bbox[2] - bbox[0]
    draw.text((x - w/2, y + 60), name, font=font, fill=(0, 0, 0))

def draw_connections(draw, actor_key, usecase_y):
    actor = ACTORS[actor_key]
    actor_x = actor["x"]
    actor_y = actor["y"]
    
    if actor_x < 480:  # Left side
        start_pt = (actor_x + 20, actor_y + 4)  # End of arm
        end_pt = (480 - 140, usecase_y)
    else:  # Right side
        start_pt = (actor_x - 20, actor_y + 4)  # End of arm
        end_pt = (480 + 140, usecase_y)
        
    draw.line([start_pt[0], start_pt[1], end_pt[0], end_pt[1]], fill=(0, 0, 0), width=2)

def xml_escape(text):
    if not text:
        return ""
    return text.replace("&", "&amp;").replace("<", "&lt;").replace(">", "&gt;").replace('"', "&quot;").replace("'", "&apos;")

def generate_use_case_drawio(output_path):
    xml_lines = []
    xml_lines.append('<?xml version="1.0" encoding="UTF-8"?>')
    xml_lines.append('<mxfile host="Electron" modified="2026-06-15T00:00:00.000Z" agent="5.0" version="20.0.0" type="device">')
    xml_lines.append('  <diagram id="usecase_diagram" name="Use Case Diagram">')
    xml_lines.append('    <mxGraphModel dx="1000" dy="1200" grid="1" gridSize="10" guides="1" tooltips="1" connect="1" arrows="1" fold="1" page="1" pageScale="1" pageWidth="960" pageHeight="1200" math="0" shadow="0">')
    xml_lines.append('      <root>')
    xml_lines.append('        <mxCell id="0"/>')
    xml_lines.append('        <mxCell id="1" parent="0"/>')
    
    # System boundary box
    xml_lines.append('        <mxCell id="sys_boundary" value="Sistem Informasi Peminjaman Ruangan dan Sarana (SIPRASA)" style="swimlane;whiteSpace=wrap;html=1;startSize=30;fillColor=none;strokeColor=#000000;strokeWidth=2;align=center;connectable=0;" vertex="1" parent="1">')
    xml_lines.append('          <mxGeometry x="240" y="30" width="480" height="1140" as="geometry"/>')
    xml_lines.append('        </mxCell>')
    
    # Draw Actors
    actor_id_map = {}
    for key, actor in ACTORS.items():
        node_id = f"actor_{key}"
        actor_id_map[key] = node_id
        xml_lines.append(f'        <mxCell id="{node_id}" value="{actor["name"]}" style="shape=umlActor;verticalLabelPosition=bottom;verticalAlign=top;html=1;outlineConnect=0;strokeWidth=2;strokeColor=#000000;" vertex="1" parent="1">')
        xml_lines.append(f'          <mxGeometry x="{actor["x"] - 15}" y="{actor["y"] - 30}" width="30" height="60" as="geometry"/>')
        xml_lines.append('        </mxCell>')
        
    # Draw Use Cases
    usecase_id_map = []
    for i, uc in enumerate(USE_CASES):
        node_id = f"uc_{i}"
        usecase_id_map.append(node_id)
        usecase_y = 60 + i * 58
        xml_lines.append(f'        <mxCell id="{node_id}" value="{xml_escape(uc["label"])}" style="ellipse;whiteSpace=wrap;html=1;strokeColor=#000000;strokeWidth=2;fillColor=#ffffff;align=center;verticalAlign=middle;" vertex="1" parent="1">')
        xml_lines.append(f'          <mxGeometry x="340" y="{usecase_y - 22}" width="280" height="44" as="geometry"/>')
        xml_lines.append('        </mxCell>')
        
    # Draw Connections
    for i, uc in enumerate(USE_CASES):
        for actor_key in uc["actors"]:
            edge_id = f"edge_{actor_key}_{i}"
            source_id = actor_id_map[actor_key]
            target_id = usecase_id_map[i]
            
            style = "endArrow=none;html=1;rounded=0;strokeColor=#000000;strokeWidth=2;"
            xml_lines.append(f'        <mxCell id="{edge_id}" style="{style}" edge="1" parent="1" source="{source_id}" target="{target_id}">')
            xml_lines.append('          <mxGeometry width="50" height="50" as="geometry"/>')
            xml_lines.append('        </mxCell>')
            
    xml_lines.append('      </root>')
    xml_lines.append('    </mxGraphModel>')
    xml_lines.append('  </diagram>')
    xml_lines.append('</mxfile>')
    
    with open(output_path, "w", encoding="utf-8") as f:
        f.write("\n".join(xml_lines))
    print(f"Generated use case draw.io: {output_path}")

def generate_use_case_png(output_path):
    canvas_width = 960
    canvas_height = 1200
    
    img = Image.new("RGBA", (canvas_width, canvas_height), (255, 255, 255, 255))
    draw = ImageDraw.Draw(img)
    
    # 1. Draw System Boundary Box
    draw.rounded_rectangle([240, 30, 720, 1170], radius=8, outline=(0, 0, 0), width=2)
    # Header separator for title
    draw.line([240, 65, 720, 65], fill=(0, 0, 0), width=2)
    # Title Text
    draw_text_centered(draw, "Sistem Informasi Peminjaman Ruangan dan Sarana (SIPRASA)", 480, 48, font_title, (0, 0, 0))
    
    # 2. Draw Connections first so they are behind elements
    for i, uc in enumerate(USE_CASES):
        usecase_y = 60 + i * 58
        for actor_key in uc["actors"]:
            draw_connections(draw, actor_key, usecase_y)
            
    # 3. Draw Use Cases (Ellipses)
    for i, uc in enumerate(USE_CASES):
        usecase_y = 60 + i * 58
        draw_use_case(draw, 480, usecase_y, uc["label"], font_regular)
        
    # 4. Draw Actors
    for key, actor in ACTORS.items():
        draw_stick_figure(draw, actor["x"], actor["y"], actor["name"], font_bold)
        
    # Save Image
    img_rgb = Image.new("RGB", img.size, (255, 255, 255))
    img_rgb.paste(img, mask=img.split()[3])
    img_rgb.save(output_path, "PNG", dpi=(300, 300))
    print(f"Generated use case PNG: {output_path}")

if __name__ == "__main__":
    output_dir = "docs/erd,fungsional,use case"
    os.makedirs(output_dir, exist_ok=True)
    
    generate_use_case_drawio(os.path.join(output_dir, "use_case.drawio"))
    generate_use_case_png(os.path.join(output_dir, "Use Case.png"))
    print("Use Case diagrams compilation completed successfully.")
