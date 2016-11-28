/* 

    This script schedules a monthly MySQL event which calls two stored procedures:
        1. `lastmile_dataportal`.`dataPortalValues`     (called for the previous three months)
        2. `lastmile_dataportal`.`leafletValues`        (called for the previous month only)

    !!!!! Document CRON jobs in this folder as well !!!!!

 */

USE `lastmile_dataportal`;
DROP EVENT IF EXISTS `evt_dataPortalValues`;

DELIMITER $$
CREATE EVENT evt_dataPortalValues
ON SCHEDULE EVERY 1 MONTH
STARTS '2016-01-15 05:00:00'
DO 
BEGIN

	-- Runs `dataPortalValues` procedure for the last three months
	-- For the last two months, this overwrites existing values
	-- This is done because of the data entry lag of 1-2 months that we often experience
	
	-- Set date variables
	SET @currDate = curdate();
	SET @currYear = year(curdate());
	SET @currMonth = month(curdate());
	SET @currYearMinus1 = year(DATE_ADD(curdate(), INTERVAL -1 MONTH));
	SET @currMonthMinus1 = month(DATE_ADD(curdate(), INTERVAL -1 MONTH));
	SET @currYearMinus2 = year(DATE_ADD(curdate(), INTERVAL -2 MONTH));
	SET @currMonthMinus2 = month(DATE_ADD(curdate(), INTERVAL -2 MONTH));
	SET @currYearMinus3 = year(DATE_ADD(curdate(), INTERVAL -3 MONTH));
	SET @currMonthMinus3 = month(DATE_ADD(curdate(), INTERVAL -3 MONTH));
	
	-- Run procedure calls
	CALL `lastmile_dataportal`.`dataPortalValues`(@currMonthMinus1, @currYearMinus1);
	CALL `lastmile_dataportal`.`dataPortalValues`(@currMonthMinus2, @currYearMinus2);
	CALL `lastmile_dataportal`.`dataPortalValues`(@currMonthMinus3, @currYearMinus3);
	CALL `lastmile_dataportal`.`leafletValues`(@currMonthMinus1, @currYearMinus1);
	
END $$
DELIMITER ;
