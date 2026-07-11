import os
import math
import PIL
from PIL import Image, ImageDraw, ImageFont

# Define diagram coordinates layout
# Width = 600px
# User center = 150px
# System center = 450px
# Lane divider = 300px
# Row Y spacing = 80px
# First row start Y = 145px

COL_USER_X = 150
COL_SYSTEM_X = 450
LANE_DIVIDER_X = 300

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

def draw_action_node(draw, x, y, label, font):
    w = 180
    h = 48
    # Draw rounded rectangle (kotak biasa, sudut sedikit membulat)
    draw.rounded_rectangle([x - w//2, y - h//2, x + w//2, y + h//2], radius=8, fill=(255, 255, 255), outline=(0, 0, 0), width=2)
    
    max_w = w - 24
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

def draw_start_node(draw, x, y):
    r = 10
    draw.ellipse([x - r, y - r, x + r, y + r], fill=(0, 0, 0))

def draw_end_node(draw, x, y):
    r_out = 12
    r_in = 6
    draw.ellipse([x - r_out, y - r_out, x + r_out, y + r_out], fill=(255, 255, 255), outline=(0, 0, 0), width=2)
    draw.ellipse([x - r_in, y - r_in, x + r_in, y + r_in], fill=(0, 0, 0))

def draw_decision_node(draw, x, y):
    s = 18
    draw.polygon([
        (x, y - s),
        (x + s, y),
        (x, y + s),
        (x - s, y)
    ], fill=(255, 255, 255), outline=(0, 0, 0), width=2)

def get_node_coords(node_type, column, row):
    x = COL_USER_X if column == "user" else COL_SYSTEM_X
    y = 145 + row * 80
    return x, y

def get_connection_offset(node_type, direction):
    if node_type == "decision":
        return 18, 18
    elif node_type == "action":
        return 90, 24
    elif node_type == "start":
        return 10, 10
    elif node_type == "end":
        return 12, 12
    return 0, 0

def draw_line_arrow(draw, points, label=None, label_side="top", font=None):
    # Draw segment lines
    for i in range(len(points) - 1):
        p1, p2 = points[i], points[i+1]
        draw.line([p1[0], p1[1], p2[0], p2[1]], fill=(0, 0, 0), width=2)
        
    # Draw arrowhead at last point
    p_last, p_prev = points[-1], points[-2]
    dx = p_last[0] - p_prev[0]
    dy = p_last[1] - p_prev[1]
    
    x, y = p_last[0], p_last[1]
    if dx > 0 and dy == 0:  # Right
        draw.polygon([(x, y), (x - 8, y - 5), (x - 8, y + 5)], fill=(0, 0, 0))
    elif dx < 0 and dy == 0:  # Left
        draw.polygon([(x, y), (x + 8, y - 5), (x + 8, y + 5)], fill=(0, 0, 0))
    elif dx == 0 and dy > 0:  # Down
        draw.polygon([(x, y), (x - 5, y - 8), (x + 5, y - 8)], fill=(0, 0, 0))
    elif dx == 0 and dy < 0:  # Up
        draw.polygon([(x, y), (x - 5, y + 8), (x + 5, y + 8)], fill=(0, 0, 0))

    # Draw label if present
    if label and font:
        mid_x = (points[0][0] + points[1][0]) / 2
        mid_y = (points[0][1] + points[1][1]) / 2
        if label_side == "top":
            draw.text((mid_x + 8, mid_y - 14), label, font=font, fill=(0, 0, 0))
        elif label_side == "side":
            draw.text((mid_x + 8, mid_y - 4), label, font=font, fill=(0, 0, 0))

def generate_activity_png(name, title_text, actor_name, nodes, connections, output_dir):
    max_row = max(node["row"] for node in nodes.values())
    canvas_height = 145 + max_row * 80 + 60
    canvas_width = 600

    img = Image.new("RGBA", (canvas_width, canvas_height), (255, 255, 255, 255))
    draw = ImageDraw.Draw(img)

    # Draw Title (plain text, no box, centered at top)
    draw_text_centered(draw, title_text, canvas_width//2, 35, font_title, (0, 0, 0))

    # Draw Lane divider and outer border (simple lines, no headers colors)
    draw.rounded_rectangle([10, 60, canvas_width - 10, canvas_height - 10], radius=8, outline=(0, 0, 0), width=2)
    draw.line([LANE_DIVIDER_X, 60, LANE_DIVIDER_X, canvas_height - 10], fill=(0, 0, 0), width=2)

    # Draw Swimlane header text (Aktor dynamically set, and Sistem)
    draw_text_centered(draw, actor_name, COL_USER_X, 82, font_bold, (0, 0, 0))
    draw_text_centered(draw, "Sistem", COL_SYSTEM_X, 82, font_bold, (0, 0, 0))
    # Line separating header from content
    draw.line([10, 105, canvas_width - 10, 105], fill=(0, 0, 0), width=2)

    # Draw Nodes
    for n_id, n in nodes.items():
        x, y = get_node_coords(n["type"], n["column"], n["row"])
        if n["type"] == "action":
            draw_action_node(draw, x, y, n["label"], font_regular)
        elif n["type"] == "decision":
            draw_decision_node(draw, x, y)
        elif n["type"] == "start":
            draw_start_node(draw, x, y)
        elif n["type"] == "end":
            draw_end_node(draw, x, y)

    # Draw Connections
    for conn in connections:
        node_from = nodes[conn["from"]]
        node_to = nodes[conn["to"]]
        
        x_from, y_from = get_node_coords(node_from["type"], node_from["column"], node_from["row"])
        x_to, y_to = get_node_coords(node_to["type"], node_to["column"], node_to["row"])
        
        dx_from, dy_from = get_connection_offset(node_from["type"], conn.get("exit", "bottom"))
        dx_to, dy_to = get_connection_offset(node_to["type"], conn.get("entry", "top"))

        route = conn.get("route", "direct")
        label = conn.get("label", None)
        label_side = conn.get("label_side", "top")

        if route == "direct":
            if x_from == x_to:
                p1 = (x_from, y_from + dy_from if y_to > y_from else y_from - dy_from)
                p2 = (x_to, y_to - dy_to if y_to > y_from else y_to + dy_to)
                draw_line_arrow(draw, [p1, p2], label, label_side, font_regular)
            elif y_from == y_to:
                p1 = (x_from + dx_from if x_to > x_from else x_from - dx_from, y_from)
                p2 = (x_to - dx_to if x_to > x_from else x_to + dx_to, y_to)
                draw_line_arrow(draw, [p1, p2], label, label_side, font_regular)
            else:
                p1 = (x_from, y_from + dy_from)
                mid_y = (y_from + y_to) / 2
                p2 = (x_from, mid_y)
                p3 = (x_to, mid_y)
                p4 = (x_to, y_to - dy_to)
                draw_line_arrow(draw, [p1, p2, p3, p4], label, label_side, font_regular)

        elif route == "orthogonal":
            p1 = (x_from, y_from + dy_from if y_to > y_from else y_from - dy_from)
            mid_y = (y_from + y_to) / 2
            p2 = (x_from, mid_y)
            p3 = (x_to, mid_y)
            p4 = (x_to, y_to - dy_to if y_to > y_from else y_to + dy_to)
            draw_line_arrow(draw, [p1, p2, p3, p4], label, label_side, font_regular)

        elif route == "loopback_left":
            p1 = (x_from + dx_from, y_from)
            loop_x = x_to - dx_to - 30
            p2 = (loop_x, y_from)
            p3 = (loop_x, y_to)
            p4 = (x_to - dx_to, y_to)
            draw_line_arrow(draw, [p1, p2, p3, p4], label, label_side, font_regular)

        elif route == "loopback_right":
            p1 = (x_from - dx_from, y_from)
            loop_x = x_to + dx_to + 30
            p2 = (loop_x, y_from)
            p3 = (loop_x, y_to)
            p4 = (x_to + dx_to, y_to)
            draw_line_arrow(draw, [p1, p2, p3, p4], label, label_side, font_regular)

        elif route == "loopback_outer_left":
            p1 = (x_from - dx_from, y_from)
            p2 = (25, y_from)
            p3 = (25, y_to)
            p4 = (x_to - dx_to, y_to)
            draw_line_arrow(draw, [p1, p2, p3, p4], label, label_side, font_regular)

        elif route == "loopback_outer_right":
            p1 = (x_from + dx_from, y_from)
            p2 = (canvas_width - 25, y_from)
            p3 = (canvas_width - 25, y_to)
            p4 = (x_to + dx_to, y_to)
            draw_line_arrow(draw, [p1, p2, p3, p4], label, label_side, font_regular)

    # Save PNG
    img_rgb = Image.new("RGB", img.size, (255, 255, 255))
    img_rgb.paste(img, mask=img.split()[3])
    filepath = os.path.join(output_dir, f"{name}.png")
    img_rgb.save(filepath, "PNG", dpi=(300, 300))
    print(f"Generated: {filepath}")

def xml_escape(text):
    if not text:
        return ""
    return text.replace("&", "&amp;").replace("<", "&lt;").replace(">", "&gt;").replace('"', "&quot;").replace("'", "&apos;")

def generate_activity_drawio(name, title_text, actor_name, nodes, connections, output_dir):
    max_row = max(node["row"] for node in nodes.values())
    canvas_height = 145 + max_row * 80 + 60
    
    xml_lines = []
    xml_lines.append('<?xml version="1.0" encoding="UTF-8"?>')
    xml_lines.append('<mxfile host="Electron" modified="2026-06-15T00:00:00.000Z" agent="5.0" version="20.0.0" type="device">')
    xml_lines.append(f'  <diagram id="{name}" name="{xml_escape(title_text)}">')
    xml_lines.append('    <mxGraphModel dx="1000" dy="1000" grid="1" gridSize="10" guides="1" tooltips="1" connect="1" arrows="1" fold="1" page="1" pageScale="1" pageWidth="827" pageHeight="1169" math="0" shadow="0">')
    xml_lines.append('      <root>')
    xml_lines.append('        <mxCell id="0"/>')
    xml_lines.append('        <mxCell id="1" parent="0"/>')
    
    # ID counter starts at 2
    cell_id = 2
    node_id_map = {}
    
    # Draw Swimlanes (Actor and Sistem side-by-side)
    # Actor lane
    actor_lane_id = cell_id
    cell_id += 1
    xml_lines.append(f'        <mxCell id="{actor_lane_id}" value="{xml_escape(actor_name)}" style="swimlane;whiteSpace=wrap;html=1;startSize=30;fillColor=none;strokeColor=#000000;strokeWidth=2;fontStyle=1;align=center;connectable=0;" vertex="1" parent="1">')
    xml_lines.append(f'          <mxGeometry x="10" y="60" width="290" height="{canvas_height - 70}" as="geometry"/>')
    xml_lines.append('        </mxCell>')
    
    # Sistem lane
    system_lane_id = cell_id
    cell_id += 1
    xml_lines.append(f'        <mxCell id="{system_lane_id}" value="Sistem" style="swimlane;whiteSpace=wrap;html=1;startSize=30;fillColor=none;strokeColor=#000000;strokeWidth=2;fontStyle=1;align=center;connectable=0;" vertex="1" parent="1">')
    xml_lines.append(f'          <mxGeometry x="300" y="60" width="290" height="{canvas_height - 70}" as="geometry"/>')
    xml_lines.append('        </mxCell>')
    
    # Draw title
    title_id = cell_id
    cell_id += 1
    # Plain text title
    xml_lines.append(f'        <mxCell id="{title_id}" value="{xml_escape(title_text)}" style="text;html=1;strokeColor=none;fillColor=none;align=center;verticalAlign=middle;whiteSpace=wrap;rounded=0;fontStyle=1;fontSize=15;" vertex="1" parent="1">')
    xml_lines.append(f'          <mxGeometry x="10" y="10" width="580" height="40" as="geometry"/>')
    xml_lines.append('        </mxCell>')
    
    # Draw Nodes
    for n_id, n in nodes.items():
        node_id = cell_id
        cell_id += 1
        node_id_map[n_id] = node_id
        
        col_x = COL_USER_X if n["column"] == "user" else COL_SYSTEM_X
        row_y = 145 + n["row"] * 80
        
        if n["type"] == "action":
            # Rounded rectangle shape
            style = "rounded=1;whiteSpace=wrap;html=1;arcSize=15;fillColor=#ffffff;strokeColor=#000000;strokeWidth=2;align=center;verticalAlign=middle;"
            w = 180
            h = 48
            x = col_x - w//2
            y = row_y - h//2
            xml_lines.append(f'        <mxCell id="{node_id}" value="{xml_escape(n["label"])}" style="{style}" vertex="1" parent="1">')
            xml_lines.append(f'          <mxGeometry x="{x}" y="{y}" width="{w}" height="{h}" as="geometry"/>')
            xml_lines.append('        </mxCell>')
        elif n["type"] == "decision":
            # Rhombus
            style = "rhombus;whiteSpace=wrap;html=1;fillColor=#ffffff;strokeColor=#000000;strokeWidth=2;align=center;verticalAlign=middle;"
            w = 36
            h = 36
            x = col_x - w//2
            y = row_y - h//2
            xml_lines.append(f'        <mxCell id="{node_id}" value="" style="{style}" vertex="1" parent="1">')
            xml_lines.append(f'          <mxGeometry x="{x}" y="{y}" width="{w}" height="{h}" as="geometry"/>')
            xml_lines.append('        </mxCell>')
        elif n["type"] == "start":
            # Start Node: solid black circle
            style = "ellipse;whiteSpace=wrap;html=1;fillColor=#000000;strokeColor=#000000;"
            w = 20
            h = 20
            x = col_x - w//2
            y = row_y - h//2
            xml_lines.append(f'        <mxCell id="{node_id}" value="" style="{style}" vertex="1" parent="1">')
            xml_lines.append(f'          <mxGeometry x="{x}" y="{y}" width="{w}" height="{h}" as="geometry"/>')
            xml_lines.append('        </mxCell>')
        elif n["type"] == "end":
            # End Node: circle inside a circle
            style = "ellipse;html=1;shape=endState;fillColor=#000000;strokeColor=#000000;strokeWidth=2;"
            w = 24
            h = 24
            x = col_x - w//2
            y = row_y - h//2
            xml_lines.append(f'        <mxCell id="{node_id}" value="" style="{style}" vertex="1" parent="1">')
            xml_lines.append(f'          <mxGeometry x="{x}" y="{y}" width="{w}" height="{h}" as="geometry"/>')
            xml_lines.append('        </mxCell>')

    # Draw Connections
    for conn in connections:
        edge_id = cell_id
        cell_id += 1
        
        from_node = node_id_map[conn["from"]]
        to_node = node_id_map[conn["to"]]
        
        label = conn.get("label", "")
        # Orthogonal edge style
        style = "edgeStyle=orthogonalEdgeStyle;rounded=0;orthogonalLoop=1;jettySize=auto;html=1;strokeColor=#000000;strokeWidth=2;endArrow=block;endFill=1;"
        if label:
            style += "labelBackgroundColor=#ffffff;"
            
        xml_lines.append(f'        <mxCell id="{edge_id}" value="{xml_escape(label)}" style="{style}" edge="1" parent="1" source="{from_node}" target="{to_node}">')
        xml_lines.append('          <mxGeometry relative="1" as="geometry"/>')
        xml_lines.append('        </mxCell>')
        
    xml_lines.append('      </root>')
    xml_lines.append('    </mxGraphModel>')
    xml_lines.append('  </diagram>')
    xml_lines.append('</mxfile>')
    
    filepath = os.path.join(output_dir, f"{name}.drawio")
    with open(filepath, "w", encoding="utf-8") as f:
        f.write("\n".join(xml_lines))
    print(f"Generated draw.io file: {filepath}")

# Helper to construct standardized CRUD workflows
def get_crud_diagram(title_text, data_singular, data_plural):
    nodes = {
        "start": {"type": "start", "column": "user", "row": 0},
        "menu": {"type": "action", "column": "user", "row": 1, "label": f"Memilih Menu Kelola {data_singular}"},
        "index": {"type": "action", "column": "system", "row": 1, "label": f"Menampilkan Daftar Data {data_plural}"},
        "add_btn": {"type": "action", "column": "user", "row": 2, "label": f"Memilih Tombol Tambah {data_singular}"},
        "form": {"type": "action", "column": "system", "row": 2, "label": "Menampilkan Form Input"},
        "input": {"type": "action", "column": "user", "row": 3, "label": f"Menginputkan Data {data_singular} & Klik Simpan"},
        "validate": {"type": "action", "column": "system", "row": 4, "label": "Memvalidasi Data"},
        "decision": {"type": "decision", "column": "system", "row": 5},
        "save": {"type": "action", "column": "system", "row": 6, "label": f"Menyimpan Data ke Database & Tampilkan Pesan Sukses"},
        "end": {"type": "end", "column": "system", "row": 7}
    }
    connections = [
        {"from": "start", "to": "menu", "route": "direct"},
        {"from": "menu", "to": "index", "route": "direct"},
        {"from": "index", "to": "add_btn", "route": "orthogonal"},
        {"from": "add_btn", "to": "form", "route": "direct"},
        {"from": "form", "to": "input", "route": "orthogonal"},
        {"from": "input", "to": "validate", "route": "orthogonal"},
        {"from": "validate", "to": "decision", "route": "direct"},
        {"from": "decision", "to": "save", "route": "direct", "label": "Ya", "label_side": "side"},
        {"from": "decision", "to": "input", "route": "loopback_outer_left", "label": "Tidak", "label_side": "top"},
        {"from": "save", "to": "end", "route": "direct"}
    ]
    return title_text, nodes, connections

# Main diagrams compiler
if __name__ == "__main__":
    output_dir = "docs/activity diagram"
    os.makedirs(output_dir, exist_ok=True)

    activity_diagrams = {}

    # Define standard CRUD diagrams based on CRUD helper
    cruds = {
        "3_kelola_pengguna": ("Kelola Pengguna (User Management)", "Aktor", "Pengguna", "Pengguna"),
        "4_kelola_landing_page": ("Kelola Landing Page", "Aktor", "Landing Page", "Landing Page"),
        "5_kelola_tamu": ("Kelola Tamu (Guest Management)", "Aktor", "Tamu", "Tamu"),
        "6_kelola_ulasan": ("Kelola Ulasan Ruangan", "Aktor", "Ulasan", "Ulasan"),
        "7_kelola_ruangan": ("Kelola Ruangan", "Aktor", "Ruangan", "Ruangan"),
        "8_kelola_sarana": ("Kelola Sarana & Prasarana", "Aktor", "Sarana", "Sarana"),
        "9_kelola_paket": ("Kelola Paket Ruangan", "Aktor", "Paket Ruangan", "Paket Ruangan"),
        "18_kelola_berita": ("Kelola Berita", "Aktor", "Berita", "Berita")
    }

    for key, (title_text, actor, ds, dp) in cruds.items():
        t, n, c = get_crud_diagram(title_text, ds, dp)
        activity_diagrams[key] = {"title": t, "actor": actor, "nodes": n, "connections": c}

    # Define custom activity diagrams
    # 1_login_sistem
    activity_diagrams["1_login_sistem"] = {
        "title": "Login Sistem",
        "actor": "Aktor",
        "nodes": {
            "start": {"type": "start", "column": "user", "row": 0},
            "open": {"type": "action", "column": "user", "row": 1, "label": "Membuka Halaman Login"},
            "show": {"type": "action", "column": "system", "row": 1, "label": "Menampilkan Form Login"},
            "input": {"type": "action", "column": "user", "row": 2, "label": "Mengisi Email & Password, klik Login"},
            "validate": {"type": "action", "column": "system", "row": 3, "label": "Memvalidasi Kredensial"},
            "decision": {"type": "decision", "column": "system", "row": 4},
            "session": {"type": "action", "column": "system", "row": 5, "label": "Membuat Session & Redirect ke Dashboard"},
            "end": {"type": "end", "column": "system", "row": 6}
        },
        "connections": [
            {"from": "start", "to": "open", "route": "direct"},
            {"from": "open", "to": "show", "route": "direct"},
            {"from": "show", "to": "input", "route": "orthogonal"},
            {"from": "input", "to": "validate", "route": "orthogonal"},
            {"from": "validate", "to": "decision", "route": "direct"},
            {"from": "decision", "to": "session", "route": "direct", "label": "Ya", "label_side": "side"},
            {"from": "decision", "to": "input", "route": "loopback_outer_left", "label": "Tidak", "label_side": "top"},
            {"from": "session", "to": "end", "route": "direct"}
        ]
    }

    # 2_registrasi_akun
    activity_diagrams["2_registrasi_akun"] = {
        "title": "Registrasi Akun",
        "actor": "Aktor",
        "nodes": {
            "start": {"type": "start", "column": "user", "row": 0},
            "open": {"type": "action", "column": "user", "row": 1, "label": "Membuka Halaman Registrasi"},
            "show": {"type": "action", "column": "system", "row": 1, "label": "Menampilkan Form Registrasi"},
            "input": {"type": "action", "column": "user", "row": 2, "label": "Mengisi Nama, Email, Password, dan Data Diri, klik Register"},
            "validate": {"type": "action", "column": "system", "row": 3, "label": "Memvalidasi Data Registrasi"},
            "decision": {"type": "decision", "column": "system", "row": 4},
            "save": {"type": "action", "column": "system", "row": 5, "label": "Menyimpan Data Akun (Belum Aktif) & Kirim Kode Verifikasi ke Email"},
            "show_otp": {"type": "action", "column": "system", "row": 6, "label": "Menampilkan Halaman Verifikasi Email"},
            "input_otp": {"type": "action", "column": "user", "row": 7, "label": "Menginputkan Kode Verifikasi & Klik Verifikasi"},
            "validate_otp": {"type": "action", "column": "system", "row": 8, "label": "Memvalidasi Kode Verifikasi"},
            "decision_otp": {"type": "decision", "column": "system", "row": 9},
            "activate": {"type": "action", "column": "system", "row": 10, "label": "Mengubah Status Akun Menjadi Aktif & Redirect ke Login"},
            "end": {"type": "end", "column": "system", "row": 11}
        },
        "connections": [
            {"from": "start", "to": "open", "route": "direct"},
            {"from": "open", "to": "show", "route": "direct"},
            {"from": "show", "to": "input", "route": "orthogonal"},
            {"from": "input", "to": "validate", "route": "orthogonal"},
            {"from": "validate", "to": "decision", "route": "direct"},
            {"from": "decision", "to": "save", "route": "direct", "label": "Valid", "label_side": "side"},
            {"from": "decision", "to": "input", "route": "loopback_outer_left", "label": "Tidak Valid", "label_side": "top"},
            {"from": "save", "to": "show_otp", "route": "direct"},
            {"from": "show_otp", "to": "input_otp", "route": "orthogonal"},
            {"from": "input_otp", "to": "validate_otp", "route": "orthogonal"},
            {"from": "validate_otp", "to": "decision_otp", "route": "direct"},
            {"from": "decision_otp", "to": "activate", "route": "direct", "label": "Valid", "label_side": "side"},
            {"from": "decision_otp", "to": "input_otp", "route": "loopback_outer_left", "label": "Tidak Valid", "label_side": "top"},
            {"from": "activate", "to": "end", "route": "direct"}
        ]
    }


    # 10_kelola_profil
    activity_diagrams["10_kelola_profil"] = {
        "title": "Kelola Profil",
        "actor": "Aktor",
        "nodes": {
            "start": {"type": "start", "column": "user", "row": 0},
            "menu": {"type": "action", "column": "user", "row": 1, "label": "Membuka Halaman Profil Saya"},
            "show": {"type": "action", "column": "system", "row": 1, "label": "Menampilkan Detail Profil & Form Edit"},
            "edit": {"type": "action", "column": "user", "row": 2, "label": "Mengubah Data Profil & Klik Simpan Perubahan"},
            "validate": {"type": "action", "column": "system", "row": 3, "label": "Memvalidasi Data Profil"},
            "decision": {"type": "decision", "column": "system", "row": 4},
            "save": {"type": "action", "column": "system", "row": 5, "label": "Menyimpan Perubahan Profil & Tampilkan Pesan Sukses"},
            "end": {"type": "end", "column": "system", "row": 6}
        },
        "connections": [
            {"from": "start", "to": "menu", "route": "direct"},
            {"from": "menu", "to": "show", "route": "direct"},
            {"from": "show", "to": "edit", "route": "orthogonal"},
            {"from": "edit", "to": "validate", "route": "orthogonal"},
            {"from": "validate", "to": "decision", "route": "direct"},
            {"from": "decision", "to": "save", "route": "direct", "label": "Valid", "label_side": "side"},
            {"from": "decision", "to": "edit", "route": "loopback_outer_left", "label": "Tidak Valid", "label_side": "top"},
            {"from": "save", "to": "end", "route": "direct"}
        ]
    }

    # 11_kelola_invoice
    activity_diagrams["11_kelola_invoice"] = {
        "title": "Kelola & Cetak Invoice",
        "actor": "Aktor",
        "nodes": {
            "start": {"type": "start", "column": "user", "row": 0},
            "open": {"type": "action", "column": "user", "row": 1, "label": "Memilih Menu Invoice / Mengklik Detail Invoice"},
            "show": {"type": "action", "column": "system", "row": 1, "label": "Menampilkan Halaman Rincian/Detail Invoice"},
            "edit": {"type": "action", "column": "user", "row": 2, "label": "Mengubah Status Pembayaran ke PAID & Klik Simpan"},
            "validate": {"type": "action", "column": "system", "row": 3, "label": "Memvalidasi Input Status Pembayaran"},
            "decision": {"type": "decision", "column": "system", "row": 4},
            "error_msg": {"type": "action", "column": "system", "row": 5, "label": "Menampilkan Notifikasi Kesalahan Input"},
            "save": {"type": "action", "column": "system", "row": 6, "label": "Update Status: PAID & LUNAS, Kirim Email, & Tampilkan Sukses"},
            "end": {"type": "end", "column": "system", "row": 7}
        },
        "connections": [
            {"from": "start", "to": "open", "route": "direct"},
            {"from": "open", "to": "show", "route": "direct"},
            {"from": "show", "to": "edit", "route": "orthogonal"},
            {"from": "edit", "to": "validate", "route": "orthogonal"},
            {"from": "validate", "to": "decision", "route": "direct"},
            {"from": "decision", "to": "save", "route": "direct", "label": "Valid", "label_side": "side"},
            {"from": "decision", "to": "error_msg", "route": "direct", "label": "Tidak Valid", "label_side": "side"},
            {"from": "error_msg", "to": "edit", "route": "loopback_outer_left"},
            {"from": "save", "to": "end", "route": "direct"}
        ]
    }

    # 12_checkin
    activity_diagrams["12_checkin"] = {
        "title": "Proses Check-In Peminjaman",
        "actor": "Aktor",
        "nodes": {
            "start": {"type": "start", "column": "user", "row": 0},
            "open": {"type": "action", "column": "user", "row": 1, "label": "Memilih Menu Transaksi & Cari Peminjaman Approved"},
            "show": {"type": "action", "column": "system", "row": 1, "label": "Menampilkan Halaman Transaksi"},
            "click": {"type": "action", "column": "user", "row": 2, "label": "Memilih Data & Klik Tombol Check-in"},
            "validate": {"type": "action", "column": "system", "row": 3, "label": "Memvalidasi Waktu Check-in"},
            "decision": {"type": "decision", "column": "system", "row": 4},
            "error_msg": {"type": "action", "column": "system", "row": 5, "label": "Menampilkan Peringatan: Belum Waktunya"},
            "active": {"type": "action", "column": "system", "row": 6, "label": "Update Status: CHECK_IN & Catat Waktu Masuk"},
            "end": {"type": "end", "column": "system", "row": 7}
        },
        "connections": [
            {"from": "start", "to": "open", "route": "direct"},
            {"from": "open", "to": "show", "route": "direct"},
            {"from": "show", "to": "click", "route": "orthogonal"},
            {"from": "click", "to": "validate", "route": "orthogonal"},
            {"from": "validate", "to": "decision", "route": "direct"},
            {"from": "decision", "to": "active", "route": "direct", "label": "Valid", "label_side": "side"},
            {"from": "decision", "to": "error_msg", "route": "direct", "label": "Tidak", "label_side": "side"},
            {"from": "error_msg", "to": "click", "route": "loopback_outer_left"},
            {"from": "active", "to": "end", "route": "direct"}
        ]
    }

    # 13_checkout
    activity_diagrams["13_checkout"] = {
        "title": "Proses Check-Out Peminjaman",
        "actor": "Aktor",
        "nodes": {
            "start": {"type": "start", "column": "user", "row": 0},
            "open": {"type": "action", "column": "user", "row": 1, "label": "Memilih Peminjaman Aktif & Klik Check-out"},
            "show": {"type": "action", "column": "system", "row": 1, "label": "Menampilkan Form Check-out"},
            "check": {"type": "action", "column": "user", "row": 2, "label": "Memeriksa Kondisi Sarana & Input Kerusakan (jika ada)"},
            "calc": {"type": "action", "column": "system", "row": 3, "label": "Menghitung Biaya Tambahan & Denda"},
            "update": {"type": "action", "column": "system", "row": 4, "label": "Update Status: CHECK_OUT, Simpan Kondisi & Cetak Kwitansi"},
            "end": {"type": "end", "column": "system", "row": 5}
        },
        "connections": [
            {"from": "start", "to": "open", "route": "direct"},
            {"from": "open", "to": "show", "route": "direct"},
            {"from": "show", "to": "check", "route": "orthogonal"},
            {"from": "check", "to": "calc", "route": "orthogonal"},
            {"from": "calc", "to": "update", "route": "direct"},
            {"from": "update", "to": "end", "route": "direct"}
        ]
    }

    # 14_verifikasi_peminjaman
    activity_diagrams["14_verifikasi_peminjaman"] = {
        "title": "Verifikasi & Approval Peminjaman",
        "actor": "Aktor",
        "nodes": {
            "start": {"type": "start", "column": "user", "row": 0},
            "open": {"type": "action", "column": "user", "row": 1, "label": "Membuka Detail Pengajuan Pending"},
            "show": {"type": "action", "column": "system", "row": 1, "label": "Menampilkan Berkas Pengajuan"},
            "verify": {"type": "action", "column": "user", "row": 2, "label": "Memeriksa Validitas & Kelayakan Berkas"},
            "decision": {"type": "decision", "column": "user", "row": 3},
            "reject": {"type": "action", "column": "user", "row": 4, "label": "Menolak & Input Alasan Penolakan"},
            "reject_sys": {"type": "action", "column": "system", "row": 4, "label": "Update Status: Rejected & Kirim email"},
            "approve": {"type": "action", "column": "user", "row": 5, "label": "Menyetujui Pengajuan Peminjaman"},
            "approve_sys": {"type": "action", "column": "system", "row": 5, "label": "Update Status: Approved & Generate Invoice"},
            "end": {"type": "end", "column": "system", "row": 6}
        },
        "connections": [
            {"from": "start", "to": "open", "route": "direct"},
            {"from": "open", "to": "show", "route": "direct"},
            {"from": "show", "to": "verify", "route": "orthogonal"},
            {"from": "verify", "to": "decision", "route": "direct"},
            {"from": "decision", "to": "reject", "route": "direct", "label": "Tolak", "label_side": "side"},
            {"from": "decision", "to": "approve", "route": "direct", "label": "Setuju", "label_side": "side"},
            {"from": "reject", "to": "reject_sys", "route": "direct"},
            {"from": "approve", "to": "approve_sys", "route": "direct"},
            {"from": "reject_sys", "to": "end", "route": "orthogonal"},
            {"from": "approve_sys", "to": "end", "route": "direct"}
        ]
    }

    # 15_ajukan_peminjaman
    activity_diagrams["15_ajukan_peminjaman"] = {
        "title": "Ajukan Peminjaman (Reservasi)",
        "actor": "Aktor",
        "nodes": {
            "start": {"type": "start", "column": "user", "row": 0},
            "open": {"type": "action", "column": "user", "row": 1, "label": "Memilih Menu Pengajuan Peminjaman"},
            "show": {"type": "action", "column": "system", "row": 1, "label": "Menampilkan Form Pengajuan"},
            "input": {"type": "action", "column": "user", "row": 2, "label": "Mengisi Formulir & Memilih Fasilitas"},
            "check": {"type": "action", "column": "system", "row": 3, "label": "Memeriksa Ketersediaan Ruangan & Sarana"},
            "decision": {"type": "decision", "column": "system", "row": 4},
            "error_msg": {"type": "action", "column": "system", "row": 5, "label": "Menampilkan Notifikasi Stok Habis / Terbooking"},
            "save": {"type": "action", "column": "system", "row": 6, "label": "Menyimpan Pengajuan dengan Status: PENDING"},
            "end": {"type": "end", "column": "system", "row": 7}
        },
        "connections": [
            {"from": "start", "to": "open", "route": "direct"},
            {"from": "open", "to": "show", "route": "direct"},
            {"from": "show", "to": "input", "route": "orthogonal"},
            {"from": "input", "to": "check", "route": "orthogonal"},
            {"from": "check", "to": "decision", "route": "direct"},
            {"from": "decision", "to": "save", "route": "direct", "label": "Tersedia", "label_side": "side"},
            {"from": "decision", "to": "error_msg", "route": "direct", "label": "Penuh", "label_side": "side"},
            {"from": "error_msg", "to": "input", "route": "loopback_outer_left"},
            {"from": "save", "to": "end", "route": "direct"}
        ]
    }

    # 16_batalkan_peminjaman
    activity_diagrams["16_batalkan_peminjaman"] = {
        "title": "Batalkan Peminjaman",
        "actor": "Aktor",
        "nodes": {
            "start": {"type": "start", "column": "user", "row": 0},
            "open": {"type": "action", "column": "user", "row": 1, "label": "Membuka Menu Peminjaman Saya"},
            "show": {"type": "action", "column": "system", "row": 1, "label": "Menampilkan Daftar Booking Aktif"},
            "click": {"type": "action", "column": "user", "row": 2, "label": "Memilih Booking & Klik Batal Peminjaman"},
            "confirm": {"type": "action", "column": "system", "row": 2, "label": "Menampilkan Modal Konfirmasi Pembatalan"},
            "decision": {"type": "decision", "column": "user", "row": 3},
            "save": {"type": "action", "column": "system", "row": 4, "label": "Update Status: Cancelled, Lepas Jadwal Booking"},
            "success": {"type": "action", "column": "system", "row": 5, "label": "Menampilkan Pesan: Pembatalan Sukses"},
            "end": {"type": "end", "column": "system", "row": 6}
        },
        "connections": [
            {"from": "start", "to": "open", "route": "direct"},
            {"from": "open", "to": "show", "route": "direct"},
            {"from": "show", "to": "click", "route": "orthogonal"},
            {"from": "click", "to": "confirm", "route": "direct"},
            {"from": "confirm", "to": "decision", "route": "orthogonal"},
            {"from": "decision", "to": "save", "route": "orthogonal", "label": "Ya", "label_side": "side"},
            {"from": "decision", "to": "click", "route": "loopback_outer_left", "label": "Tidak"},
            {"from": "save", "to": "success", "route": "direct"},
            {"from": "success", "to": "end", "route": "direct"}
        ]
    }

    # 17_lihat_laporan
    activity_diagrams["17_lihat_laporan"] = {
        "title": "Lihat Laporan Penggunaan",
        "actor": "Aktor",
        "nodes": {
            "start": {"type": "start", "column": "user", "row": 0},
            "open": {"type": "action", "column": "user", "row": 1, "label": "Memilih Menu Laporan"},
            "show": {"type": "action", "column": "system", "row": 1, "label": "Menampilkan Form Filter Laporan"},
            "filter": {"type": "action", "column": "user", "row": 2, "label": "Menginputkan Range Tanggal & Klik Filter"},
            "query": {"type": "action", "column": "system", "row": 3, "label": "Query & Aggregasi Data Penggunaan Fasilitas"},
            "render": {"type": "action", "column": "system", "row": 4, "label": "Menampilkan Grafik & Rincian Laporan"},
            "print_choice": {"type": "action", "column": "user", "row": 5, "label": "Memilih Aksi Cetak PDF (Opsional)"},
            "pdf": {"type": "action", "column": "system", "row": 6, "label": "Generate & Download File PDF Laporan"},
            "end": {"type": "end", "column": "system", "row": 7}
        },
        "connections": [
            {"from": "start", "to": "open", "route": "direct"},
            {"from": "open", "to": "show", "route": "direct"},
            {"from": "show", "to": "filter", "route": "orthogonal"},
            {"from": "filter", "to": "query", "route": "orthogonal"},
            {"from": "query", "to": "render", "route": "direct"},
            {"from": "render", "to": "print_choice", "route": "orthogonal"},
            {"from": "print_choice", "to": "pdf", "route": "direct"},
            {"from": "pdf", "to": "end", "route": "direct"}
        ]
    }

    # 19_publish_berita
    activity_diagrams["19_publish_berita"] = {
        "title": "Publish Berita",
        "actor": "Aktor",
        "nodes": {
            "start": {"type": "start", "column": "user", "row": 0},
            "open": {"type": "action", "column": "user", "row": 1, "label": "Membuka Detail Berita Draf"},
            "show": {"type": "action", "column": "system", "row": 1, "label": "Menampilkan Rincian Berita & Tombol Publish"},
            "click": {"type": "action", "column": "user", "row": 2, "label": "Mengklik Tombol Publish Berita"},
            "confirm": {"type": "action", "column": "system", "row": 2, "label": "Menampilkan Konfirmasi Publikasi"},
            "decision": {"type": "decision", "column": "user", "row": 3},
            "publish": {"type": "action", "column": "system", "row": 4, "label": "Mengubah Status Berita ke PUBLISHED & Tampilkan ke Publik"},
            "success": {"type": "action", "column": "system", "row": 5, "label": "Menampilkan Pesan: Berita Berhasil Dipublish"},
            "end": {"type": "end", "column": "system", "row": 6}
        },
        "connections": [
            {"from": "start", "to": "open", "route": "direct"},
            {"from": "open", "to": "show", "route": "direct"},
            {"from": "show", "to": "click", "route": "orthogonal"},
            {"from": "click", "to": "confirm", "route": "direct"},
            {"from": "confirm", "to": "decision", "route": "orthogonal"},
            {"from": "decision", "to": "publish", "route": "orthogonal", "label": "Ya"},
            {"from": "decision", "to": "open", "route": "loopback_outer_left", "label": "Tidak"},
            {"from": "publish", "to": "success", "route": "direct"},
            {"from": "success", "to": "end", "route": "direct"}
        ]
    }

    # Set of expected file names
    valid_names = {
        "1_login_sistem", "2_registrasi_akun", "3_kelola_pengguna", "4_kelola_landing_page",
        "5_kelola_tamu", "6_kelola_ulasan", "7_kelola_ruangan", "8_kelola_sarana", "9_kelola_paket",
        "10_kelola_profil", "11_kelola_invoice", "12_checkin", "13_checkout", "14_verifikasi_peminjaman",
        "15_ajukan_peminjaman", "16_batalkan_peminjaman", "17_lihat_laporan", "18_kelola_berita",
        "19_publish_berita"
    }

    # Cleanup old diagram files
    for filename in os.listdir(output_dir):
        base, ext = os.path.splitext(filename)
        if ext in [".png", ".drawio"]:
            if base not in valid_names:
                filepath = os.path.join(output_dir, filename)
                try:
                    os.remove(filepath)
                    print(f"Removed old diagram file: {filepath}")
                except Exception as e:
                    print(f"Failed to remove {filepath}: {e}")

    # Run generator
    print(f"Compiling {len(activity_diagrams)} Activity Diagrams...")
    for filename, data in activity_diagrams.items():
        generate_activity_png(filename, data["title"], data["actor"], data["nodes"], data["connections"], output_dir)
        generate_activity_drawio(filename, data["title"], data["actor"], data["nodes"], data["connections"], output_dir)

    print("Activity Diagrams generation completed successfully.")
