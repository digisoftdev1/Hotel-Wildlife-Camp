import axios from 'axios';
import SUNEDITOR from 'suneditor';
import plugins from 'suneditor/plugins';

const filteredPlugins = { ...plugins };
delete filteredPlugins.exportPDF;
delete filteredPlugins.fileUpload;
delete filteredPlugins.layout;
delete filteredPlugins.template;
delete filteredPlugins.math;
delete filteredPlugins.image;
delete filteredPlugins.video;
delete filteredPlugins.audio;
delete filteredPlugins.embed;
delete filteredPlugins.drawing;
delete filteredPlugins.imageGallery;
delete filteredPlugins.videoGallery;
delete filteredPlugins.audioGallery;
delete filteredPlugins.fileGallery;
delete filteredPlugins.fileBrowser;

window.axios = axios;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
window.SUNEDITOR = SUNEDITOR;
window.plugins = filteredPlugins;