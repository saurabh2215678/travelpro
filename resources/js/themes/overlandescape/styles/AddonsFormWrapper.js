import styled from "vue3-styled-components";

export const AddonsFormWrapper = styled.div`
& .fts_top input[type="checkbox"] {
    accent-color: #12968E;
    width: 20px;
    height: 20px;
    position: relative;
    top: 3px;
    cursor: pointer;
}
& .inputval {
    display: flex;
    align-items: center;
    justify-content: center;
    input {
        max-width: 85px;
        border-color: var(--theme-color);
        border-left: 0;
        border-right: 0;
        border-radius:0;
        text-align: center;
 }
  button, [type='button'] {
    border: 1px solid var(--theme-color);
    font-size: 0.8rem;
    line-height: 1.42857;
    padding: 0.5rem 0.8rem;
    border-radius: 5px;
    color: var(--theme-color);
    :hover{
        background: #00968830;
    }
}
}
& .inputval button[type='button']:last-child {
    border-bottom-left-radius: 0;
    border-top-left-radius: 0;
}

& .inputval button[type='button']:first-child {
    border-bottom-right-radius: 0;
    border-top-right-radius: 0;
}

`