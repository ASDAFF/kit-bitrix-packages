import React from 'react';
import './empty-basket.scss';

const EmptyBasket = ({title, isShow, linkToCatalog}) => {
    return (
        <div className={`empty-basket`}>
            <div className={`empty-basket__image`}>
                <svg className={`empty-basket__icon ${isShow == 'basket' ? '' : 'hide'}`} width="72" height="80">
                    <use xlinkHref="/local/templates/sotbit_origami/assets/img/sprite.svg#icon_right_cart_empty"></use>
                </svg>

                <svg className={`empty-favorite__icon ${isShow == 'favorite' ? '' : 'hide'}`} width="72" height="80">
                    <use xlinkHref="/local/templates/sotbit_origami/assets/img/sprite.svg#icon_right_favorite_empty"></use>
                </svg>
            </div>
            <p className={`empty-basket__title`}>
                {isShow === 'favorite' ? title.REACT_BASKET_DELAY_EMPTY : title.REACT_BASKET_EMPTY}
            </p>
            <p className={`empty-basket__description`}>
                {isShow === 'favorite' ? title.REACT_BASKET_DELAY_EMPTY_DESCRIPTION : title.REACT_BASKET_EMPTY_DESCRIPTION}
            </p>
            <a href= {linkToCatalog}  className={`empty-basket__link`}>
                {isShow === 'favorite' ? title.REACT_BASKET_DELAY_EMPTY_LINK : title.REACT_BASKET_EMPTY_LINK}
            </a>
        </div>
    );
};

export default EmptyBasket;
