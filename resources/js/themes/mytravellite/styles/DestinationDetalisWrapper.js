import styled from "vue3-styled-components";

export const DestinationDetalisWrapper = styled.section`
& .top_title {
		font-size: 1.5rem;
		font-weight: 600;
		/* font-family: 'PT Serif',serif;
		color: var(--theme-color);
		text-transform: uppercase; 
		margin-bottom: 1.8rem;*/
		&:after{
			content: "";
			display: block;
			background-color: var(--secondary-color);
			height: 3px;
			width: 40px;
		}
	
}
& .gallery .blocks-gallery-grid li.blocks-gallery-item{
  flex-grow: inherit;
}
& .blocks-gallery-grid{
    display: flex;
    flex-wrap: wrap;
    list-style-type: none;
    padding: 0;
    margin: 0;
    width: 100%;
  li.blocks-gallery-item {
    margin: 0 0.825rem 0.938rem 0;
    display: flex;
    flex-grow: 1;
    flex-direction: column;
    justify-content: center;
    width: min-content;
    height: 18.75rem;
    overflow: hidden;
    flex-basis: 30.33%;
    /* padding-left: 0.625rem; */
    }
li.blocks-gallery-item img {
    height: 100%;
    object-fit: cover;
    width: 100%;
    }
}

@media (max-width: 1070px){
    
}
@media (max-width: 767px){
  & .blocks-gallery-grid li.blocks-gallery-item {
    height: 7.75rem;
    flex-basis: 46.33%;
  }
}
`;