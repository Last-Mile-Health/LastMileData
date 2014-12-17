CREATE 
    ALGORITHM = UNDEFINED 
    DEFINER = `root`@`localhost` 
    SQL SECURITY DEFINER
VIEW `lastmiledata`.`view_reg_current-population` AS
    select 
        `view_reg_registration`.`fhwID` AS `fhwID`,
        `view_reg_registration`.`memberID` AS `memberID`,
        `view_reg_registration`.`hhID` AS `hhID`,
        `view_reg_registration`.`firstName` AS `firstName`,
        `view_reg_registration`.`lastName` AS `lastName`,
        `view_reg_registration`.`sex` AS `sex`,
        `view_reg_registration`.`dob` AS `dob`
    from
        `lastmiledata`.`view_reg_registration`
    where
        (not (`view_reg_registration`.`memberID` in (select 
                `view_bdm_deaths`.`memberID`
            from
                `lastmiledata`.`view_bdm_deaths` union select 
                `view_bdm_moveouts`.`memberID`
            from
                `lastmiledata`.`view_bdm_moveouts`))) 
    union select 
        `view_bdm_births`.`fhwID` AS `fhwID`,
        `view_bdm_births`.`memberID` AS `memberID`,
        `view_bdm_births`.`hhID` AS `hhID`,
        `view_bdm_births`.`firstName` AS `firstName`,
        `view_bdm_births`.`lastName` AS `lastName`,
        `view_bdm_births`.`sex` AS `sex`,
        `view_bdm_births`.`dob` AS `dob`
    from
        `lastmiledata`.`view_bdm_births`
    where
        (not (`view_bdm_births`.`memberID` in (select 
                `view_bdm_deaths`.`memberID`
            from
                `lastmiledata`.`view_bdm_deaths` union select 
                `view_bdm_moveouts`.`memberID`
            from
                `lastmiledata`.`view_bdm_moveouts`))) 
    union select 
        `view_bdm_moveins`.`fhwID` AS `fhwID`,
        `view_bdm_moveins`.`memberID` AS `memberID`,
        `view_bdm_moveins`.`hhID` AS `hhID`,
        `view_bdm_moveins`.`firstName` AS `firstName`,
        `view_bdm_moveins`.`lastName` AS `lastName`,
        `view_bdm_moveins`.`sex` AS `sex`,
        `view_bdm_moveins`.`dob` AS `dob`
    from
        `lastmiledata`.`view_bdm_moveins`
    where
        (not (`view_bdm_moveins`.`memberID` in (select 
                `view_bdm_deaths`.`memberID`
            from
                `lastmiledata`.`view_bdm_deaths` union select 
                `view_bdm_moveouts`.`memberID`
            from
                `lastmiledata`.`view_bdm_moveouts`)))
