export default {
  async onLinkPickerGetOptions(options) {
    options.push(...(await import('./linkPickerGetOptions')).default);
  },
};
