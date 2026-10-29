"""Package only custom source; never runtime, secrets, or third-party dependencies."""
from pathlib import Path
from zipfile import ZipFile, ZIP_DEFLATED
root = Path(__file__).resolve().parent.parent
out = root / 'releases'
out.mkdir(exist_ok=True)
for folder, name in [('wordpress/wp-content/themes/muneeb-mart','muneeb-mart-theme.zip'),('wordpress/wp-content/plugins/muneeb-mart-core','muneeb-mart-core.zip')]:
    base = root / folder
    with ZipFile(out/name,'w',ZIP_DEFLATED) as archive:
        for item in base.rglob('*'):
            if item.is_file(): archive.write(item, item.relative_to(base.parent))
with ZipFile(out/'muneeb-mart-source.zip','w',ZIP_DEFLATED) as archive:
    for folder in ['wordpress/wp-content/themes/muneeb-mart','wordpress/wp-content/plugins/muneeb-mart-core','docs','demo-content','scripts','.github']:
        for item in (root/folder).rglob('*'):
            if item.is_file(): archive.write(item, Path('muneeb-mart')/item.relative_to(root))
    for name in ['README.md','.gitignore','.env.example','compose.yaml','package.json','LICENSE']:
        archive.write(root/name,Path('muneeb-mart')/name)
for name in ['muneeb-mart-theme.zip','muneeb-mart-core.zip','muneeb-mart-source.zip']:
    with ZipFile(out/name) as archive:
        assert archive.testzip() is None
        assert not any('.local/' in f or 'wp-config.php' in f or f.endswith('.sql') for f in archive.namelist())
        print(f'{name}: {len(archive.namelist())} files, { (out/name).stat().st_size:,} bytes, validated')
import hashlib
(out/'SHA256SUMS.txt').write_text(''.join(hashlib.sha256((out/name).read_bytes()).hexdigest()+'  '+name+'\n' for name in ['muneeb-mart-theme.zip','muneeb-mart-core.zip','muneeb-mart-source.zip']))
