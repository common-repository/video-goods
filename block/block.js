(function (blocks, editor, components, i18n, element) {
    var el = wp.element.createElement
    var registerBlockType = wp.blocks.registerBlockType
    var RichText = wp.editor.RichText
    var BlockControls = wp.editor.BlockControls
    var AlignmentToolbar = wp.editor.AlignmentToolbar
    var MediaUpload = wp.editor.MediaUpload
    var InspectorControls = wp.editor.InspectorControls
    var TextControl = components.TextControl
    var SelectControl = components.SelectControl
    var CheckboxControl = components.CheckboxControl
    var ColorPicker = components.ColorPicker
    var ToggleControl = components.ToggleControl
    var RangeControl = components.RangeControl

    registerBlockType('venby/venby-block', { // The name of our block. Must be a string with prefix. Example: my-plugin/my-custom-block.
        title: i18n.__('Venby'), // The title of our block.
        description: i18n.__('Venby block.'), // The description of our block.
        icon: { background: '#fffff', foreground: '#555D66', src: 'format-video'}, // Dashicon icon for our block. Custom icons can be added using inline SVGs.
        category: 'widgets', // The category of the block.
        attributes: { // Necessary for saving block content.
            shortcode: {
                type: 'array',
                source: 'children',
                selector: 'p'
            },
            selectCampaign: {
                type: 'string'
            },
            selectPopUpType: {
                type: 'string'
            },
            showCustomButtonSettings: {
                type: 'string'
            },
            customButtonText: {//button text
                type: 'string'
            },
            customButtonFontColor: {//button font color
                type: 'string'
            },
            customButtonBackgroundColor: {//button background color
                type: 'string'
            },

            /*********************/
            customCartBackgroundColor: {//Cart background color
                type: 'string'
            },

            customLinksColor: {//Links color
                type: 'string'
            },
            /*********************/

            customButtonBorderColor: {//button border color
                type: 'string'
            },
            showCustomLinkSettings: {
                type: 'string'
            },
            customLinkText: {//custom link settings
                type: 'string'
            },
            showThumbnailImageSettings: {
                type: 'string'
            },
            thumbnailImageWidth: {//thumbnail image width setting
                type: 'number',
                value: 300
            },
            thumbnailImageHeight: {//thumbnail image height setting
                type: 'number',
                value: 200
            },
            /*****BUTTON SIZE*****/
            buttonHeight: {
                type: 'number',
                value: 60
            },
            buttonWidth: {
                type: 'number',
                value: 180
            },
            /***********/
            exitCustomText: {
                type: 'string'
            },
            autoPopUp: {
                type: 'boolean'
            },

            cartPopup: {
                type: 'boolean'
            },

            showBackground: {
                type: 'boolean'
            }
        },

        edit: function (props) {
            var attributes = props.attributes;
            var selectCampaign = props.attributes.selectCampaign;
            var selectCampaignOptions = document.querySelectorAll('#vg-edit-form select[name="cid"] option');
            var selectCampaignOptionsArray = [];

            for(var i = 1; i < selectCampaignOptions.length; ++i){
                selectCampaignOptionsArray.push({ label: selectCampaignOptions[i].text, value: selectCampaignOptions[i].value });
            }

            var selectPopUpType = props.attributes.selectPopUpType;
            var selectPopUpTypeOptions = document.querySelectorAll('#vg-edit-form #embed_type_select option');
            var selectPopUpTypeOptionsArray = [];

            for(var i = 1; i < selectPopUpTypeOptions.length; ++i){
                selectPopUpTypeOptionsArray.push({ label: selectPopUpTypeOptions[i].text, value: selectPopUpTypeOptions[i].value });
            }

            var onSelectCampaign = function (campaignType) {
                return props.setAttributes({
                    selectCampaign: campaignType
                })
            }

            var onSelectPopUpType = function (PopUpType) {
                return props.setAttributes({
                    selectPopUpType: PopUpType
                })
            }


            var customButtonText = props.attributes.customButtonText;
            var customButtonFontColor = props.attributes.customButtonFontColor;
            var customButtonBackgroundColor = props.attributes.customButtonBackgroundColor;
            /****************/
            var customCartBackgroundColor = props.attributes.customCartBackgroundColor;
            var customLinksColor = props.attributes.customLinksColor;
            /******************************/
            var customButtonBorderColor = props.attributes.customButtonBorderColor;
            var showCustomButtonSettings = props.attributes.showCustomButtonSettings;

            // custom link pop up
            var customLinkText = props.attributes.customLinkText;
            var showCustomLinkSettings = props.attributes.showCustomLinkSettings;

            //thumbnail image pop up
            var thumbnailImageWidth = props.attributes.thumbnailImageWidth;
            var thumbnailImageHeight = props.attributes.thumbnailImageHeight;
            var showThumbnailImageSettings = props.attributes.showThumbnailImageSettings;

            // general settings
            var exitCustomText = props.attributes.exitCustomText;
            var autoPopUp = props.attributes.autoPopUp;
            var cartPopup = props.attributes.cartPopup;
            var showBackground = props.attributes.showBackground;

            //Button size
            var buttonHeight = props.attributes.buttonHeight;
            var buttonWidth = props.attributes.buttonWidth;

            if(!selectCampaign){
                props.setAttributes({ selectCampaign: selectCampaignOptionsArray[0].value});
            }

            if(!selectPopUpType){
                props.setAttributes({ selectPopUpType: selectPopUpTypeOptionsArray[0].value });
                props.setAttributes({ showCustomLinkSettings: 'hidden' });
                props.setAttributes({ showCustomButtonSettings: 'hidden' });
            }

            if(!customButtonFontColor){
                props.setAttributes({ customButtonFontColor: '#ffffff' });
            }
            if(!customButtonBackgroundColor){
                props.setAttributes({ customButtonBackgroundColor: '#000000' });
            }

            /**********************************/
            if(!customCartBackgroundColor){
                props.setAttributes({ customCartBackgroundColor: '#262E2F' });
            }

            if(!customLinksColor){
                props.setAttributes({ customLinksColor: '#00a2ff' });
            }
            /**********************************/
            return [
                el(InspectorControls, { key: 'inspector' }, // Display the block options in the inspector panel.
                    el(components.PanelBody, {
                            title: i18n.__('Venby Settings'),
                            className: 'venby-setings',
                            initialOpen: true
                        },
                        // Campaign selector
                        el(SelectControl, {
                            label: i18n.__('Insert a campaign'),
                            value: selectCampaign,
                            options: selectCampaignOptionsArray,
                            onChange: function (campaign) {
                                props.setAttributes({ selectCampaign: campaign })
                            }
                        }),
                        // PopUpType selector
                        el(SelectControl, {
                            label: i18n.__('Select trigger action'),
                            value: selectPopUpType,
                            options: selectPopUpTypeOptionsArray,
                            onChange: function (popUpType) {
                                props.setAttributes({ selectPopUpType: popUpType })
                                props.setAttributes({ showCustomButtonSettings: 'hidden' })
                                props.setAttributes({ showCustomLinkSettings: 'hidden' })
                                props.setAttributes({ showThumbnailImageSettings: 'hidden' })
                                let additinalSettings = document.querySelectorAll('.additinal-settings');
                                for(let i = 0; i < additinalSettings.length; ++i){
                                    additinalSettings[i].classList.add('hidden');
                                }
                                if(popUpType == 'trigger'){
                                    props.setAttributes({ showCustomButtonSettings: '' })
                                    document.querySelector('.venby-button-setings').classList.remove('hidden');
                                }else if(popUpType == 'custom'){
                                    props.setAttributes({ showCustomLinkSettings: '' })
                                    document.querySelector('.venby-custom-link-setings').classList.remove('hidden');
                                }else if(popUpType == 'thumbnail'){
                                    props.setAttributes({ showThumbnailImageSettings: '' })
                                    document.querySelector('.venby-thumbnail-setings').classList.remove('hidden');
                                }
                            }
                        }),
                    ),
                    el(components.PanelBody, {
                            title: i18n.__('Custom Button Settings'),
                            className: 'venby-button-setings additinal-settings ' + showCustomButtonSettings,
                            initialOpen: true
                        },
                        // cusotom button pop up text
                        el(TextControl, {
                            label: i18n.__('Add Custom Button Text'),
                            value: customButtonText,
                            placeholder: i18n.__('Custom Button'),
                            onChange: function (text) {
                                props.setAttributes({ customButtonText: text })
                            }
                        }),
                        el('div',
                            {className: 'venby-custom-button-font-color', style: {marginBottom: '4px'}}, 'Custom Button Font Color'),
                        // custom button font color
                        el(ColorPicker, {
                            color: customButtonFontColor,
                            onChangeComplete: function (color) {
                                props.setAttributes({ customButtonFontColor: color.hex })
                            }
                        }),
                        el('div', {className: 'venby-custom-button-background-color', style: {marginBottom: '4px'}}, 'Custom Button Background Color'),
                        // custom button background color
                        el(ColorPicker, {
                            color: customButtonBackgroundColor,
                            onChangeComplete: function (color) {
                                props.setAttributes({ customButtonBackgroundColor: color.hex })
                            }
                        }),

                        el('div', {className: 'venby-custom-button-border-color-block'},
                            el('div', {className: 'venby-custom-button-border-color', style: {marginBottom: '4px'}}, 'Custom Button Border Color'),
                            // custom button background color
                            el(ColorPicker, {
                                color: customButtonBorderColor,
                                onChangeComplete: function (color) {
                                    props.setAttributes({ customButtonBorderColor: color.hex })
                                }
                            }),
                        ),
                        /********BUTTON size********/
                        el(components.PanelBody, {
                                title: i18n.__('Select Custom Button Size'),
                                className: ''
                            },
                            el(RangeControl, {
                                label: i18n.__('Width px'),
                                value: buttonWidth,
                                min: 100,
                                max: 1000,
                                onChange: function (number) {
                                    props.setAttributes({ buttonWidth: number })
                                }
                            }),
                            el(RangeControl, {
                                label: i18n.__('Height px'),
                                value: buttonHeight,
                                min: 50,
                                max: 1000,
                                onChange: function (number) {
                                    props.setAttributes({ buttonHeight: number })
                                }
                            })
                        ),
                        /********END BUTTON size********/

                    ),
                    el(components.PanelBody, {
                            title: i18n.__('Thumbnail image pop up'),
                            className: 'venby-thumbnail-setings additinal-settings ' + showThumbnailImageSettings,
                            initialOpen: true
                        },
                        // thumbnail image width
                        el(RangeControl, {
                            label: i18n.__('Width px'),
                            value: thumbnailImageWidth,
                            min: 300,
                            max: 1000,
                            onChange: function (number) {
                                props.setAttributes({ thumbnailImageWidth: number })
                            }
                        }),
                        // thumbnail image height
                        el(RangeControl, {
                            label: i18n.__('Height px'),
                            value: thumbnailImageHeight,
                            min: 100,
                            max: 1000,
                            onChange: function (number) {
                                props.setAttributes({ thumbnailImageHeight: number })
                            }
                        })
                    ),

                    el(components.PanelBody, {
                            title: i18n.__('Custom link click trigger'),
                            className: 'venby-custom-link-setings additinal-settings ' + showCustomLinkSettings,
                            initialOpen: true
                        },
                        // custom link text
                        el(TextControl, {
                            label: i18n.__('Custom link'),
                            value: customLinkText,
                            onChange: function (text) {
                                props.setAttributes({ customLinkText: text })
                            }
                        })
                    ),

                    el(components.PanelBody, {
                            title: i18n.__('Genaral settings'),
                            className: 'venby-setings',
                            initialOpen: true
                        },
                        el('div',
                            {
                                className: 'venby-custom-button-bacground',
                                style: {marginBottom: '4px'}
                            },
                            'links color'
                        ),
                        // links color
                        el(ColorPicker, {
                            color: customLinksColor,
                            onChangeComplete: function (color) {
                                props.setAttributes({ customLinksColor: color.hex })
                            }
                        }),
                        // exit custom text
                        el(TextControl, {
                            label: i18n.__('Customize Exit Button Text'),
                            value: exitCustomText,
                            placeholder: i18n.__('EXIT'),
                            onChange: function (text) {
                                props.setAttributes({ exitCustomText: text })
                            }
                        }),
                        // auto pop up
                        el(ToggleControl, {
                            label: i18n.__('Activate pop-up on page load'),
                            checked: autoPopUp,
                            onChange: function (check) {
                                props.setAttributes({ autoPopUp: check })

                            }
                        }),
                        el(ToggleControl, {
                            label: i18n.__('Activate shopping cart pop-up'),
                            checked: cartPopup,
                            onChange: function (check) {
                                props.setAttributes({ cartPopup: check })
                                props.setAttributes({ showBackground: check })

                            }
                        }),
                        el(ToggleControl, {
                            label: i18n.__('Hide background image'),
                            checked: showBackground,
                            onChange: function (check) {
                                props.setAttributes({ showBackground: check })
                            }
                        }),

                        el('div',
                            {
                                className: 'venby-custom-button-bacground',
                                style: {marginBottom: '4px'}
                            },
                            'Custom Cart Background'
                        ),
                        (cartPopup == true) ?

                                el(ColorPicker, {
                                    color: customCartBackgroundColor,
                                    onChangeComplete: function (color) {
                                        props.setAttributes({ customCartBackgroundColor: color.hex })
                                    }
                                })
                        :
                            el(ColorPicker, {
                                className: 'hideCBC',
                                color: customCartBackgroundColor,
                                onChangeComplete: function (color) {
                                    props.setAttributes({ customCartBackgroundColor: color.hex })
                                }
                            })

                    ),
                ),
                el('div', { className: props.className + ' venby-container trigger-mode trigger' },
                    (selectPopUpType == 'trigger') ?
                        el('button', {
                                style: {
                                    backgroundColor: customButtonBackgroundColor,
                                    color: customButtonFontColor,
                                    border: (customButtonBorderColor) ? '1px solid '+customButtonBorderColor : 'none',
                                    padding: '1em 2em',
                                    borderRadius: '2px',
                                    width: (buttonWidth) ? buttonWidth : '' ,
                                    height: (buttonHeight) ? buttonHeight : ''
                                }
                            },
                            customButtonText
                        ) : (selectPopUpType == 'thumbnail') ? el('div', {className: 'venby-img', style: { width: thumbnailImageWidth, height: thumbnailImageHeight, display: 'inline-block', backgroundColor: '#ddd', backgroundImage: 'url(data:image/svg+xml;base64,PHN2ZyBmaWxsPSIjRkZGRkZGIiBoZWlnaHQ9IjI0IiB2aWV3Qm94PSIwIDAgMjQgMjQiIHdpZHRoPSIyNCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KICAgIDxwYXRoIGQ9Ik0wIDBoMjR2MjRIMHoiIGZpbGw9Im5vbmUiLz4KICAgIDxwYXRoIGQ9Ik0xMiAyQzYuNDggMiAyIDYuNDggMiAxMnM0LjQ4IDEwIDEwIDEwIDEwLTQuNDggMTAtMTBTMTcuNTIgMiAxMiAyem0tMiAxNC41di05bDYgNC41LTYgNC41eiIvPgo8L3N2Zz4=)', backgroundPosition: 'center', backgroundRepeat: 'no-repeat', backgroundSize: '40px 40px', minWidth: '300px', minHeight: '200px'}}, '')
                        : (selectPopUpType == 'custom') ? el('a', { href: '#' }, customLinkText) : 'select pop up type'
                )
            ]
        },

        save: function (props) {
            var attributes = props.attributes


            var customButtonBorder = (attributes.customButtonBorderColor) ? attributes.customButtonBorderColor : '';
            var autoPopUp = (attributes.autoPopUp) ? 'on' : '';
            var cartPopup = (attributes.cartPopup) ? 'true' : '';
            var showBackground = (attributes.showBackground) ? 'on' : '';
            var exitCustomText = (attributes.exitCustomText) ? attributes.exitCustomText : '';
            //console.log('-----'+attributes.buttonWidth); customCartBackgroundColor
            return (
                el('div', { className: props.className },
                    '[venby cid="'+attributes.selectCampaign+'" t="'+attributes.selectPopUpType+'" w="'+attributes.thumbnailImageWidth+'" h="'+attributes.thumbnailImageHeight+'" ctt="" tt="'+attributes.customButtonText+'" fc="'+attributes.customButtonFontColor+'" bgc="'+attributes.customButtonBackgroundColor+'" bbr="on" brc="'+customButtonBorder+'" et="'+exitCustomText+'" ap="'+autoPopUp+'" ctt="'+attributes.customLinkText+'" cbs="'+attributes.buttonWidth+'px|'+attributes.buttonHeight+'px" sb="'+showBackground+'" cpu="'+cartPopup+'" cbc="'+attributes.customCartBackgroundColor+'" clc="'+attributes.customLinksColor+'"]'
                )
            )
        }
    })

})(
    window.wp.blocks,
    window.wp.editor,
    window.wp.components,
    window.wp.i18n,
    window.wp.element
)


