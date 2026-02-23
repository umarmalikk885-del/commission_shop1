
import re

file_path = r'c:\Users\Administrator\Desktop\commission_shop1\resources\views\payment.blade.php'

with open(file_path, 'r', encoding='utf-8') as f:
    content = f.read()

# 1. Remove specific CSS blocks
css_to_remove = [
    r'\.data-table\s*\{[^}]*\}',
    r'\.data-table\s+th,\s*\.data-table\s+td\s*\{[^}]*\}',
    r'\.data-table\s+th\s*\{[^}]*\}',
    r'\.data-table\s+td\s*\{[^}]*\}',
    r'\.data-table\s+tr:hover\s+td\s*\{[^}]*\}',
    r'\.data-table\s+input\s*\{[^}]*\}',
    r'\.data-table\s+input:focus\s*\{[^}]*\}',
    r'\.col-cream\s*\{[^}]*\}',
    r'\.col-cream\s+input\s*\{[^}]*\}',
    r'\.col-blue\s*\{[^}]*\}',
    r'\.col-blue\s+input\s*\{[^}]*\}',
    r'\.col-gray\s*\{[^}]*\}',
    r'\.col-gray\s+input\s*\{[^}]*\}',
    r'\.col-dark-blue\s*\{[^}]*\}',
    r'\.col-dark-blue\s+input\s*\{[^}]*\}',
    r'\.col-maroon\s*\{[^}]*\}',
    r'\.col-maroon\s+input\s*\{[^}]*\}',
    # Dark mode overrides
    r'body\.dark-mode\s+\.data-table\s*\{[^}]*\}', # This might not exist exactly like this but checking
    r'body\.dark-mode\s+th\s*\{[^}]*\}',
    r'body\.dark-mode\s+td\s*\{[^}]*\}',
]

new_content = content
for pattern in css_to_remove:
    new_content = re.sub(pattern, '', new_content, flags=re.MULTILINE | re.DOTALL)

# 2. Add Simple Table CSS
simple_css = """
        /* Simple, Clean Table Styles */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
            background-color: transparent;
        }
        
        th, td {
            border: 1px solid #ccc;
            padding: 6px;
            text-align: center;
            font-size: 11px;
            color: #333;
        }

        th {
            background-color: #f5f5f5;
            font-weight: bold;
        }
        
        table input {
            width: 100%;
            border: none;
            background: transparent;
            text-align: center;
            font-size: 11px;
            color: #333;
        }
        
        table input:focus {
            outline: 1px solid #999;
            background-color: #f9f9f9;
        }

        /* Dark mode adjustments for simple tables */
        body.dark-mode table input {
            color: #e2e8f0;
        }
        body.dark-mode th {
            background-color: #334155;
            color: #e2e8f0;
            border-color: #475569;
        }
        body.dark-mode td {
            border-color: #475569;
            color: #e2e8f0;
        }
"""

# Insert simple CSS before the closing </style>
new_content = new_content.replace('</style>', simple_css + '\n    </style>')

# 3. Remove classes from HTML
# Remove class="data-table"
new_content = re.sub(r'class="data-table"', '', new_content)
# Remove class="col-..."
new_content = re.sub(r'class="col-[^"]*"', '', new_content)

# 4. Clean up empty class attributes if any
new_content = re.sub(r'class=""', '', new_content)

with open(file_path, 'w', encoding='utf-8') as f:
    f.write(new_content)

print("Successfully updated payment.blade.php")
